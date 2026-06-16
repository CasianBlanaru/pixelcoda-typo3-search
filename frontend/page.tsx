import { Typo3Page, getTypo3PageData } from '@pixelcoda/headless-nextjs'
import { notFound } from 'next/navigation'

export default async function Page(props: { params: Promise<{ slug?: string[] }> }) {
  const params = await props.params
  const slug = params.slug?.join('/') ?? ''
  const data = await getTypo3PageData(slug)

  if (!data) {
    notFound()
  }

  return (
    <Typo3Page data={data} />
  )
}