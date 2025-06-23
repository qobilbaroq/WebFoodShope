import { BrowserRouter } from "react-router-dom";
import ReactDOM from "react-dom/client";
import App from "./router/App";
import "../css/app.css"

ReactDOM.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <App/>
    </BrowserRouter>
)