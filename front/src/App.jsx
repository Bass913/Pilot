import React, { useState, useEffect } from "react";
import SearchBarSection from "./sections/SearchBarSection.jsx";
import { NavLink, useLocation } from "react-router-dom";
import DefaultLayout from "./layouts/DefaultLayout.jsx";

function App() {
	const [searchTerm, setSearchTerm] = useState("");
	const location = useLocation();

	useEffect(() => {
		const searchParams = new URLSearchParams(location.search);
		const term = searchParams.get("search");
		if (term !== searchTerm) {
			setSearchTerm(term || "");
		}

		if (searchTerm?.length) {
			fetchRecipes();
		}
	}, [location, searchTerm]);

	return (
		<DefaultLayout>
			<div className="flex flex-col items-center mx-auto">
				<SearchBarSection
					isTall={!searchTerm.length}
					initialValue={searchTerm}
				/>
			</div>
		</DefaultLayout>
	);
}

export default App;
