import { createRoot } from 'react-dom/client';

// Borra el contenido HTML existente
document.body.innerHTML = '<div id="app"></div>';

// Renderiza tu componente React en su lugar
const root = createRoot(document.getElementById('app'));
root.render(<h1>¡Hola, mundo!</h1>);
