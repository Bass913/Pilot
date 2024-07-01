import { createContext, useState, useContext, useEffect } from "react";
import apiService from "../services/apiService";

export const UserContext = createContext();

const getUserInfo = (user) => {
    return {
        id: user.id,
        firstname: user.firstname,
        lastname: user.lastname,
        roles: user.roles,
        companyId: user.company ? user.company.split("/").pop() : null,
    };
};

function getStoredItem(key, defaultValue) {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : defaultValue;
}

async function fetchUser() {
    try {
        const response = await apiService.getMe();
        const user = response.data;
        return getUserInfo(user);
    } catch (error) {
        console.error("Failed to fetch user:", error);
        return null;
    }
}

export const UserProvider = ({ children }) => {
    const [user, setUser] = useState(() => getStoredItem("user", null));
    const [token, setToken] = useState(() => getStoredItem("token", null));
    const [serviceSelected, setServiceSelected] = useState(() =>
        getStoredItem("serviceSelected", null),
    );
    const [timeSlotSelected, setTimeSlotSelected] = useState(() =>
        getStoredItem("timeSlotSelected", null),
    );
    const [employeeSelected, setEmployeeSelected] = useState(() =>
        getStoredItem("employeeSelected", null),
    );
    const [language, setLanguage] = useState(() =>
        getStoredItem("language", "fr"),
    );
    const [sidebarLarge, setSidebarLarge] = useState(() =>
        getStoredItem("sidebarLarge", false),
    );

    useEffect(() => {
        const initializeUser = async () => {
            const user = await fetchUser();
            setUser(user);
        };

        if (token) {
            initializeUser();
        }
    }, []);

    useEffect(() => {
        localStorage.setItem("token", JSON.stringify(token));
    }, [token]);

    useEffect(() => {
        localStorage.setItem("user", JSON.stringify(user));
    }, [user]);

    useEffect(() => {
        localStorage.setItem(
            "serviceSelected",
            JSON.stringify(serviceSelected),
        );
    }, [serviceSelected]);

    useEffect(() => {
        localStorage.setItem(
            "timeSlotSelected",
            JSON.stringify(timeSlotSelected),
        );
    }, [timeSlotSelected]);

    useEffect(() => {
        localStorage.setItem(
            "employeeSelected",
            JSON.stringify(employeeSelected),
        );
    }, [employeeSelected]);

    useEffect(() => {
        localStorage.setItem("language", JSON.stringify(language));
    }, [language]);

    useEffect(() => {
        localStorage.setItem("sidebarLarge", sidebarLarge.toString());
    }, [sidebarLarge]);

    const login = async (email, password) => {
        try {
            const response = await fetch(
                `${import.meta.env.VITE_API_URL}api/login_check`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ email, password }),
                },
            );

            const data = await response.json();
            setToken(data.token);
            setUser(data.user);

            return data;
        } catch (error) {
            console.error("Failed to login:", error);
            throw error;
        }
    };

    const logout = () => {
        setToken(null);
        setUser(null);
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/";
    };

    return (
        <UserContext.Provider
            value={{
                user,
                setUser,
                login,
                logout,
                serviceSelected,
                setServiceSelected,
                timeSlotSelected,
                setTimeSlotSelected,
                employeeSelected,
                setEmployeeSelected,
                language,
                setLanguage,
                sidebarLarge,
                setSidebarLarge,
            }}
        >
            {children}
        </UserContext.Provider>
    );
};

export const useUser = () => {
    const context = useContext(UserContext);
    if (!context) {
        throw new Error("useUser must be used within a UserProvider");
    }
    return context;
};
