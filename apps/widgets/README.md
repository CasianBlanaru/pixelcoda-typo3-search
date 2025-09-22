# @pixelcoda/widgets

```tsx
import { SearchBox, AnswerPanel } from '@pixelcoda/widgets';

function App(){
  const [res, setRes] = useState<any>(null);
  return (<>
    <SearchBox apiBase={process.env.NEXT_PUBLIC_API!} project="demo" apiKey={process.env.NEXT_PUBLIC_KEY!} onResults={setRes}/>
    <pre>{JSON.stringify(res,null,2)}</pre>
  </>);
}
```

A11y: role=search, live regions, semantic headings for answers/citations.
