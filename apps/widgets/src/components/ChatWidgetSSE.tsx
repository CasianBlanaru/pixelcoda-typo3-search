/**
 * Advanced Chat Widget with SSE Streaming
 * Features: Real-time streaming, citations, focus trap, accessibility
 */

import React, { useState, useEffect, useRef, useCallback } from 'react';
import { createPortal } from 'react-dom';

interface Citation {
  id: string;
  title: string;
  url: string;
  snippet: string;
  reference: string;
  score?: number;
}

interface Message {
  id: string;
  type: 'user' | 'assistant' | 'system';
  content: string;
  citations?: Citation[];
  timestamp: Date;
  streaming?: boolean;
}

interface ChatWidgetProps {
  apiUrl: string;
  apiKey: string;
  projectId: string;
  language?: string;
  collections?: string[];
  placeholder?: string;
  title?: string;
  position?: 'bottom-right' | 'bottom-left' | 'top-right' | 'top-left';
  theme?: 'light' | 'dark' | 'auto';
  onClose?: () => void;
}

export const ChatWidgetSSE: React.FC<ChatWidgetProps> = ({
  apiUrl,
  apiKey,
  projectId,
  language = 'de',
  collections = [],
  placeholder = 'Stellen Sie Ihre Frage...',
  title = 'Assistent',
  position = 'bottom-right',
  theme = 'auto',
  onClose
}) => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [currentStreamingMessage, setCurrentStreamingMessage] = useState<string>('');
  const [citations, setCitations] = useState<Citation[]>([]);
  const [error, setError] = useState<string | null>(null);
  
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);
  const eventSourceRef = useRef<EventSource | null>(null);
  const chatContainerRef = useRef<HTMLDivElement>(null);

  // Auto-scroll to bottom
  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages, currentStreamingMessage]);

  // Focus management
  useEffect(() => {
    if (isOpen && inputRef.current) {
      inputRef.current.focus();
    }
  }, [isOpen]);

  // Focus trap
  useEffect(() => {
    if (!isOpen) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') {
        handleClose();
      }
      
      // Focus trap logic
      if (e.key === 'Tab' && chatContainerRef.current) {
        const focusableElements = chatContainerRef.current.querySelectorAll(
          'button, input, textarea, a[href], [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0] as HTMLElement;
        const lastElement = focusableElements[focusableElements.length - 1] as HTMLElement;

        if (e.shiftKey && document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    };

    document.addEventListener('keydown', handleKeyDown);
    return () => document.removeEventListener('keydown', handleKeyDown);
  }, [isOpen]);

  // Handle close
  const handleClose = useCallback(() => {
    setIsOpen(false);
    if (eventSourceRef.current) {
      eventSourceRef.current.close();
      eventSourceRef.current = null;
    }
    onClose?.();
  }, [onClose]);

  // Submit question
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!input.trim() || isLoading) return;

    const userMessage: Message = {
      id: `msg-${Date.now()}`,
      type: 'user',
      content: input.trim(),
      timestamp: new Date()
    };

    setMessages(prev => [...prev, userMessage]);
    setInput('');
    setIsLoading(true);
    setError(null);
    setCurrentStreamingMessage('');
    setCitations([]);

    try {
      // Close any existing connection
      if (eventSourceRef.current) {
        eventSourceRef.current.close();
      }

      // Create SSE connection
      const url = `${apiUrl}/v1/ask/${projectId}/stream`;
      const body = JSON.stringify({
        q: userMessage.content,
        lang: language,
        collections,
        maxPassages: 6,
        temperature: 0.7
      });

      // Use fetch with ReadableStream for POST with SSE
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${apiKey}`,
          'Accept': 'text/event-stream'
        },
        body
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const reader = response.body?.getReader();
      const decoder = new TextDecoder();
      let buffer = '';
      let fullAnswer = '';

      if (reader) {
        while (true) {
          const { done, value } = await reader.read();
          if (done) break;

          buffer += decoder.decode(value, { stream: true });
          const lines = buffer.split('\n');
          buffer = lines.pop() || '';

          for (const line of lines) {
            if (line.startsWith('data: ')) {
              try {
                const data = JSON.parse(line.slice(6));
                handleSSEMessage(data, fullAnswer);
                
                if (data.event === 'answer_chunk' && data.text) {
                  fullAnswer += data.text;
                }
              } catch (e) {
                console.error('Failed to parse SSE data:', e);
              }
            }
          }
        }
      }

      // Add final assistant message
      if (fullAnswer || currentStreamingMessage) {
        const assistantMessage: Message = {
          id: `msg-${Date.now()}`,
          type: 'assistant',
          content: fullAnswer || currentStreamingMessage,
          citations: citations.length > 0 ? citations : undefined,
          timestamp: new Date()
        };
        setMessages(prev => [...prev, assistantMessage]);
        setCurrentStreamingMessage('');
      }

    } catch (err) {
      console.error('Chat error:', err);
      setError(err instanceof Error ? err.message : 'Ein Fehler ist aufgetreten');
      
      const errorMessage: Message = {
        id: `msg-${Date.now()}`,
        type: 'system',
        content: 'Entschuldigung, es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.',
        timestamp: new Date()
      };
      setMessages(prev => [...prev, errorMessage]);
    } finally {
      setIsLoading(false);
    }
  };

  // Handle SSE messages
  const handleSSEMessage = (data: any, currentAnswer: string) => {
    switch (data.event) {
      case 'status':
        // Could show status updates in UI
        console.log('Status:', data);
        break;
        
      case 'citations':
        if (Array.isArray(data)) {
          setCitations(data);
        }
        break;
        
      case 'answer_chunk':
        if (data.text && !data.done) {
          setCurrentStreamingMessage(prev => prev + data.text);
        }
        break;
        
      case 'error':
        setError(data.message || 'Ein Fehler ist aufgetreten');
        break;
    }
  };

  // Position classes
  const positionClasses = {
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4'
  };

  // Render citation
  const renderCitation = (citation: Citation) => (
    <a
      key={citation.id}
      href={citation.url}
      target="_blank"
      rel="noopener noreferrer"
      className="block p-2 mb-2 border rounded hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
      aria-label={`Quelle: ${citation.title}`}
    >
      <div className="flex items-start gap-2">
        <span className="text-blue-600 dark:text-blue-400 font-medium">
          {citation.reference}
        </span>
        <div className="flex-1">
          <div className="font-medium text-sm">{citation.title}</div>
          <div className="text-xs text-gray-600 dark:text-gray-400 mt-1">
            {citation.snippet}
          </div>
        </div>
      </div>
    </a>
  );

  // Render message
  const renderMessage = (message: Message) => {
    const isUser = message.type === 'user';
    const isSystem = message.type === 'system';
    
    return (
      <div
        key={message.id}
        className={`mb-4 ${isUser ? 'text-right' : 'text-left'}`}
        role="article"
        aria-label={`${message.type} Nachricht`}
      >
        <div
          className={`inline-block max-w-[80%] p-3 rounded-lg ${
            isUser
              ? 'bg-blue-600 text-white'
              : isSystem
              ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-900 dark:text-yellow-100'
              : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100'
          }`}
        >
          <div className="whitespace-pre-wrap">{message.content}</div>
          
          {message.citations && message.citations.length > 0 && (
            <div className="mt-3 pt-3 border-t border-gray-300 dark:border-gray-600">
              <div className="text-sm font-medium mb-2">Quellen:</div>
              {message.citations.map(renderCitation)}
            </div>
          )}
          
          <div className="text-xs opacity-70 mt-2">
            {message.timestamp.toLocaleTimeString()}
          </div>
        </div>
      </div>
    );
  };

  return createPortal(
    <>
      {/* Chat Button */}
      {!isOpen && (
        <button
          onClick={() => setIsOpen(true)}
          className={`fixed ${positionClasses[position]} z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all hover:scale-110`}
          aria-label="Chat öffnen"
        >
          <svg
            className="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
            />
          </svg>
        </button>
      )}

      {/* Chat Window */}
      {isOpen && (
        <div
          ref={chatContainerRef}
          className={`fixed ${positionClasses[position]} z-50 w-96 h-[600px] bg-white dark:bg-gray-900 rounded-lg shadow-2xl flex flex-col`}
          role="dialog"
          aria-label={title}
          aria-modal="true"
        >
          {/* Header */}
          <div className="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-gray-100">
              {title}
            </h2>
            <button
              onClick={handleClose}
              className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
              aria-label="Chat schließen"
            >
              <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          {/* Messages */}
          <div 
            className="flex-1 overflow-y-auto p-4"
            role="log"
            aria-live="polite"
            aria-label="Chat-Verlauf"
          >
            {messages.length === 0 && (
              <div className="text-center text-gray-500 dark:text-gray-400 mt-8">
                <svg
                  className="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                  />
                </svg>
                <p>Stellen Sie eine Frage, um zu beginnen</p>
              </div>
            )}
            
            {messages.map(renderMessage)}
            
            {currentStreamingMessage && (
              <div className="mb-4 text-left">
                <div className="inline-block max-w-[80%] p-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                  <div className="whitespace-pre-wrap">{currentStreamingMessage}</div>
                  <div className="inline-block ml-2">
                    <span className="animate-pulse">●</span>
                  </div>
                </div>
              </div>
            )}
            
            {isLoading && !currentStreamingMessage && (
              <div className="mb-4 text-left">
                <div className="inline-block p-3 rounded-lg bg-gray-100 dark:bg-gray-800">
                  <div className="flex items-center gap-2">
                    <div className="animate-spin rounded-full h-4 w-4 border-2 border-gray-500 border-t-transparent"></div>
                    <span className="text-gray-600 dark:text-gray-400">Denke nach...</span>
                  </div>
                </div>
              </div>
            )}
            
            {error && (
              <div className="mb-4 p-3 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg">
                {error}
              </div>
            )}
            
            <div ref={messagesEndRef} />
          </div>

          {/* Input */}
          <form onSubmit={handleSubmit} className="p-4 border-t dark:border-gray-700">
            <div className="flex gap-2">
              <input
                ref={inputRef}
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                placeholder={placeholder}
                disabled={isLoading}
                className="flex-1 px-3 py-2 border dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100"
                aria-label="Nachricht eingeben"
              />
              <button
                type="submit"
                disabled={isLoading || !input.trim()}
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                aria-label="Nachricht senden"
              >
                <svg
                  className="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                  />
                </svg>
              </button>
            </div>
          </form>
        </div>
      )}
    </>,
    document.body
  );
};

export default ChatWidgetSSE;
