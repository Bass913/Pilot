import { createContext, useState, useContext, useEffect } from "react";

export const UserContext = createContext();

function getServiceSelected() {
	const service = localStorage.getItem("serviceSelected");
	return service ? JSON.parse(service) : null;
}

function getUser() {
	const user = localStorage.getItem("user");
	return user ? JSON.parse(user) : null;
}

function getToken() {
	return localStorage.getItem("token");
}

export const UserProvider = ({ children }) => {
	const [user, setUser] = useState(getUser);
	const [token, setToken] = useState(getToken);
	const [serviceSelected, setServiceSelected] = useState(getServiceSelected);

	useEffect(() => {
		localStorage.setItem(
			"serviceSelected",
			JSON.stringify(serviceSelected)
		);
	}, [serviceSelected]);

	useEffect(() => {
		localStorage.setItem("user", JSON.stringify(user));
	}, [user]);

	useEffect(() => {
		localStorage.setItem("token", token);
	}, [token]);

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
