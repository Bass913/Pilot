import { createContext, useState, useContext } from "react";

export const UserContext = createContext();

export const UserProvider = ({ children }) => {
	const [user, setUser] = useState(null);
	const [loading, setLoading] = useState(true);

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
			localStorage.setItem("token", response.token);
			localStorage.setItem("user", JSON.stringify(response.user));
			setUser(response.user);
			return response;
		});
	};

	const getUserInfo = async () => {
		// try {
		// 	setLoading(true);
		// 	const response = await fetch(
		// 		"https://cuisineconnect-9ffq.onrender.com/users/me",
		// 		{
		// 			credentials: "include",
		// 		}
		// 	);
		// 	const userData = await response.json();
		// 	setUser(userData);
		// 	setLoading(false);
		// 	if (!response.ok) {
		// 		throw new Error("Something went wrong, request failed!");
		// 	}
		// } catch (err) {
		// 	console.log(err);
		// 	setUser(null);
		// 	setLoading(false);
		// }
		// get user from local storage
		const token = localStorage.getItem("token");
		const user = localStorage.getItem("user");
		if (!user || !token) {
			return;
		}
		setUser(JSON.parse(user));
	};

	const logout = () => {
		// try {
		// 	return fetch(
		// 		"https://cuisineconnect-9ffq.onrender.com/auth/logout",
		// 		{
		// 			method: "DELETE",
		// 			credentials: "include",
		// 		}
		// 	).then(() => {
		// 		setUser(null);
		// 	});
		// } catch (error) {
		// 	console.log(error);
		// }
		localStorage.removeItem("token");
		localStorage.removeItem("user");
		setUser(null);
	};

	return (
		<UserContext.Provider
			value={{ user, setUser, loading, getUserInfo, login, logout }}
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
