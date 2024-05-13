import React, { useState } from "react";
// import axiosInstance from "@/utils/axiosInstance";

function UserProfileField({ model, value, userId }) {
	const [isEditing, setIsEditing] = useState(false);
	const [newValue, setNewValue] = useState(value);

	const toggleEditing = () => {
		if (isEditing) {
			// axiosInstance
			// 	.patch(`/users/${userId}`, {
			// 		[model.name]: newValue,
			// 	})
			// 	.then(() => {
			// 		setIsEditing(false);
			// 		localStorage.setItem(
			// 			"user",
			// 			JSON.stringify({
			// 				...JSON.parse(localStorage.getItem("user") || "{}"),
			// 				[model.name]: newValue,
			// 			})
			// 		);
			// 	});
		} else {
			setIsEditing(true);
		}
	};

	const updateValue = (event) => {
		setNewValue(event.target.value);
	};

	const formatDate = (dateString) => {
		if (!dateString) return "";
		const dateObject = new Date(dateString);
		return dateObject.toLocaleDateString("fr-FR", {
			year: "numeric",
			month: "long",
			day: "numeric",
		});
	};

	const formatDateForInput = (dateString) => {
		if (model.name === "birthdate" && dateString) {
			const date = new Date(dateString);
			if (!isNaN(date.getTime())) {
				const year = date.getUTCFullYear();
				const month = String(date.getUTCMonth() + 1).padStart(2, "0");
				const day = String(date.getUTCDate()).padStart(2, "0");
				return `${year}-${month}-${day}`;
			}
		}
		return dateString;
	};

	return (
		<div className="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
			<dt className="text-sm font-medium text-gray-500">{model.label}</dt>
			<dd className="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
				{!isEditing ? (
					<span className="flex-grow">
						{model.name === "birthdate"
							? formatDate(value)
							: value}
					</span>
				) : (
					<input
						type={model.name === "birthdate" ? "date" : "text"}
						className="flex-grow px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
						value={formatDateForInput(value)}
						onChange={updateValue}
					/>
				)}
				<span className="ml-4 flex-shrink-0">
					<button
						type="button"
						className="rounded-md font-medium text-primary-600 hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/60 focus:ring-offset-2"
						onClick={toggleEditing}
					>
						{isEditing
							? "Enregistrer"
							: value
								? "Modifier"
								: "Ajouter"}
					</button>
				</span>
			</dd>
		</div>
	);
}

export default UserProfileField;
