import React from "react";
import { useEffect } from "react";
import { useUser } from "../hooks/useUser";
import Header from "../components/Header.jsx";
import Footer from "../components/Footer.jsx";

function DefaultLayout({ children }) {
	const { getUserInfo, user } = useUser();

	useEffect(() => {
		const fetchData = async () => {
			getUserInfo();
		};
		fetchData();
	}, []);

	return (
		<div className="flex flex-col min-h-screen">
			<Header user={user} />
			<main className="flex-1 mt-20">{children}</main>
			<Footer />
		</div>
	);
}

export default DefaultLayout;
