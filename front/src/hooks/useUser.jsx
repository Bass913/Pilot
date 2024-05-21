import { createContext, useState, useContext, useEffect } from "react";

export const UserContext = createContext();

function getLanguage() {
	const language = localStorage.getItem("language");
	return language ? JSON.parse(language) : "fr";
}

function getServiceSelected() {
	const service = localStorage.getItem("serviceSelected");
	return service ? JSON.parse(service) : null;
}

function getTimeSlotSelected() {
	const timeSlot = localStorage.getItem("timeSlotSelected");
	return timeSlot ? JSON.parse(timeSlot) : null;
}

function getEmployeeSelected() {
	const employee = localStorage.getItem("employeeSelected");
	return employee ? JSON.parse(employee) : null;
}

function getUser() {
	const user = localStorage.getItem("user");
	return user ? JSON.parse(user) : null;
}

function getToken() {
	return localStorage.getItem("token");
}

function getSideBarLarge() {
	return localStorage.getItem("sidebarLarge") === "true";
}

export const UserProvider = ({ children }) => {
	const [user, setUser] = useState(getUser);
	const [token, setToken] = useState(getToken);
	const [serviceSelected, setServiceSelected] = useState(getServiceSelected);
	const [timeSlotSelected, setTimeSlotSelected] =
		useState(getTimeSlotSelected);
	const [employeeSelected, setEmployeeSelected] =
		useState(getEmployeeSelected);
	const [language, setLanguage] = useState(getLanguage);
	const [sidebarLarge, setSidebarLarge] = useState(getSideBarLarge);

	useEffect(() => {
		localStorage.setItem(
			"serviceSelected",
			JSON.stringify(serviceSelected)
		);
	}, [serviceSelected]);

	useEffect(() => {
		localStorage.setItem(
			"timeSlotSelected",
			JSON.stringify(timeSlotSelected)
		);
	}, [timeSlotSelected]);

	useEffect(() => {
		localStorage.setItem(
			"employeeSelected",
			JSON.stringify(employeeSelected)
		);
	}, [employeeSelected]);

	useEffect(() => {
		localStorage.setItem("user", JSON.stringify(user));
	}, [user]);

	useEffect(() => {
		localStorage.setItem("token", token);
	}, [token]);

	useEffect(() => {
		localStorage.setItem("language", JSON.stringify(language));
	}, [language]);

	useEffect(() => {
		localStorage.setItem("sidebarLarge", sidebarLarge.toString());
	}, [sidebarLarge]);

	const login = async (email, password) => {
		return fetch("https://localhost/api/login_check", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ email, password }),
		}).then(async (res) => {
			// await getUserInfo();
			const response = await res.json();
			setToken(response.token);
			setUser(response.user);
			return response;
		});
	};

	const logout = () => {
		setToken(null);
		setUser(null);
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
