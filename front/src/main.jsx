import React from "react";
import ReactDOM from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import App from "./App.jsx";
import Root from "./layouts/RootLayout.jsx";
import Login from "./pages/auth/LoginPage.jsx";
import Register from "./pages/auth/RegisterPage.jsx";
import { UserProvider } from "./hooks/useUser.jsx";
import "./index.css";
import BecomeAPartner from "./pages/BecomeAPartnerPage.jsx";
import ProviderList from "./pages/ProviderListPage.jsx";
import ProviderDetail from "./pages/ProviderDetailPage.jsx";
import ProviderReservation from "./pages/ProviderReservationPage.jsx";

const router = createBrowserRouter([
	{
		path: "/",
		element: <Root />,
		children: [
			{
				path: "",
				element: <App />,
			},
			{
				path: "become-a-partner",
				element: <BecomeAPartner />,
			},
			{
				path: "providers",
				element: <ProviderList />,
			},
			{
				path: "provider",
				children: [
					{
						path: ":id/reservation",
						element: <ProviderReservation />,
					},
					{
						path: ":id",
						element: <ProviderDetail />,
					},
				],
			},
			{
				path: "auth",
				children: [
					{
						path: "login",
						element: <Login />,
					},
					{
						path: "register",
						element: <Register />,
					},
				],
			},
		],
	},
]);

ReactDOM.createRoot(document.getElementById("root")).render(
	<React.StrictMode>
		<UserProvider>
			<RouterProvider router={router} />
		</UserProvider>
	</React.StrictMode>
);
