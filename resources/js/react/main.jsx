import { createRoot } from 'react-dom/client';
import EleveApp from './EleveApp.jsx';

const rootElement = document.getElementById('eleves-react-root');

if (rootElement) {
    createRoot(rootElement).render(<EleveApp />);
}
