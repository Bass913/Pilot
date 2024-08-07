import React from "react";
import ReactDOM from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import App from "./App.jsx";
import Root from "./layouts/RootLayout.jsx";
import Login from "./pages/auth/LoginPage.jsx";
import Register from "./pages/auth/RegisterPage.jsx";
import { UserProvider } from "./hooks/useUser.jsx";
import "./index.css";
import i18n from "./i18n";
import { I18nextProvider } from "react-i18next";
import BecomeAPartner from "./pages/BecomeAPartnerPage.jsx";
import ProviderList from "./pages/ProviderListPage.jsx";
import ProviderDetail from "./pages/ProviderDetailPage.jsx";
import Reservation from "./pages/ReservationPage.jsx";
import Confirmation from "./pages/ConfirmationPage.jsx";
import Profile from "./pages/authenticated/ProfilePage.jsx";
import Dashboard from "./pages/admin/DashboardPage.jsx";
import RequestsPage from "./pages/admin/RequestsPage.jsx";
import DynamicEntityPage from "./pages/admin/DynamicEntityPage.jsx";
import ProtectedRoute from "./components/security/ProtectedRoute.jsx";
import EntityEditPage from "./pages/admin/EntityEditPage.jsx";
import EntityCreatePage from "./pages/admin/EntityCreatePage.jsx";
import SchedulesPage from "./pages/admin/SchedulesPage.jsx";

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
                path: "companies",
                children: [
                    {
                        path: ":id/reservation",
                        element: <Reservation />,
                    },
                    {
                        path: ":id/confirmation",
                        element: <Confirmation />,
                    },
                    {
                        path: ":id",
                        element: <ProviderDetail />,
                    },
                    {
                        path: "",
                        element: <ProviderList />,
                    },
                ],
            },
            {
                path: "profile",
                element: (
                    <ProtectedRoute
                        element={Profile}
                        allowedRoles={["ROLE_USER"]}
                    />
                ),
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
            {
                path: "admin",
                children: [
                    {
                        path: "",
                        element: (
                            <ProtectedRoute
                                element={Dashboard}
                                allowedRoles={["ROLE_EMPLOYEE", "ROLE_ADMIN"]}
                            />
                        ),
                    },
                    {
                        path: "profile",
                        element: (
                            <ProtectedRoute
                                element={Profile}
                                allowedRoles={["ROLE_EMPLOYEE", "ROLE_ADMIN"]}
                            />
                        ),
                    },
                    {
                        path: "providers",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={DynamicEntityPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="provider"
                                    />
                                ),
                            },
                            {
                                path: "create",
                                element: (
                                    <ProtectedRoute
                                        element={EntityCreatePage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="provider"
                                    />
                                ),
                            },
                            {
                                path: ":id/edit",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="provider"
                                    />
                                ),
                            },
                        ],
                    },
                    {
                        path: "services",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={DynamicEntityPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="service"
                                    />
                                ),
                            },
                            {
                                path: "create",
                                element: (
                                    <ProtectedRoute
                                        element={EntityCreatePage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="service"
                                    />
                                ),
                            },
                            {
                                path: ":id/edit",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="service"
                                    />
                                ),
                            },
                        ],
                    },
                    {
                        path: "employees",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={DynamicEntityPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="employee"
                                    />
                                ),
                            },
                            {
                                path: "create",
                                element: (
                                    <ProtectedRoute
                                        element={EntityCreatePage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="employee"
                                    />
                                ),
                            },
                            {
                                path: ":id/edit",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={["ROLE_ADMIN"]}
                                        model="employee"
                                    />
                                ),
                            },
                        ],
                    },
                    {
                        path: "schedule",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={SchedulesPage}
                                        allowedRoles={[
                                            "ROLE_EMPLOYEE",
                                            "ROLE_ADMIN",
                                        ]}
                                    />
                                ),
                            },
                            // {
                            //     path: ":id/edit",
                            //     element: (
                            //         <ProtectedRoute
                            //             element={EntityEditPage}
                            //             allowedRoles={[
                            //                 "ROLE_EMPLOYEE",
                            //                 "ROLE_ADMIN",
                            //             ]}
                            //             model="schedule"
                            //         />
                            //     ),
                            // },
                        ],
                    },
                    {
                        path: "bookings",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={DynamicEntityPage}
                                        allowedRoles={[
                                            "ROLE_EMPLOYEE",
                                            "ROLE_ADMIN",
                                        ]}
                                        model="booking"
                                    />
                                ),
                            },
                            // {
                            //     path: "create",
                            //     element: (
                            //         <ProtectedRoute
                            //             element={EntityCreatePage}
                            //             allowedRoles={[
                            //                 "ROLE_EMPLOYEE",
                            //                 "ROLE_ADMIN",
                            //             ]}
                            //             model="booking"
                            //         />
                            //     ),
                            // },
                            {
                                path: ":id/edit",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={[
                                            "ROLE_EMPLOYEE",
                                            "ROLE_ADMIN",
                                        ]}
                                        model="booking"
                                    />
                                ),
                            },
                        ],
                    },
                    {
                        path: "users",
                        children: [
                            {
                                path: "",
                                element: (
                                    <ProtectedRoute
                                        element={DynamicEntityPage}
                                        allowedRoles={["ROLE_SUPERADMIN"]}
                                        model="user"
                                    />
                                ),
                            },
                            {
                                path: "create",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={["ROLE_SUPERADMIN"]}
                                        model="user"
                                    />
                                ),
                            },
                            {
                                path: ":id/edit",
                                element: (
                                    <ProtectedRoute
                                        element={EntityEditPage}
                                        allowedRoles={["ROLE_SUPERADMIN"]}
                                        model="user"
                                    />
                                ),
                            },
                        ],
                    },
                    {
                        path: "requests",
                        element: (
                            <ProtectedRoute
                                element={RequestsPage}
                                allowedRoles={["ROLE_SUPERADMIN"]}
                            />
                        ),
                    },
                ],
            },
        ],
    },
]);

router.subscribe(() => window.scrollTo(0, 0));

ReactDOM.createRoot(document.getElementById("root")).render(
    // <React.StrictMode>
    <I18nextProvider i18n={i18n}>
        <UserProvider>
            <RouterProvider router={router} />
        </UserProvider>
    </I18nextProvider>,
    // </React.StrictMode>
);
