import React, { useState } from "react";
// import axiosInstance from "@/utils/axiosInstance";

function PasswordChanger({ userId }) {
	const [isEditing, setIsEditing] = useState(false);
	const [oldPassword, setOldPassword] = useState("");
	const [newPassword, setNewPassword] = useState("");
	const [confirmPassword, setConfirmPassword] = useState("");
	const [passwordError, setPasswordError] = useState("");
	const [confirmation, setConfirmation] = useState(false);

	const toggleEditing = async () => {
		if (isEditing) {
			if (newPassword !== confirmPassword) {
				setPasswordError("Les mots de passe ne correspondent pas.");
				return;
			}
			if (
				!newPassword.length ||
				!newPassword.length ||
				!confirmPassword.length
			) {
				setPasswordError("Veuillez remplir tous les champs.");
				return;
			}
			try {
				// await axiosInstance.patch(`/users/${userId}`, {
				// 	oldPassword,
				// 	newPassword,
				// });
				setIsEditing(false);
				setConfirmation(true);
			} catch (error) {
				if (error.response.status === 401) {
					setPasswordError("Le mot de passe actuel est incorrect.");
				}
			}
		} else {
			setIsEditing(true);
		}
	};

	return (
		<>
			{!isEditing && (
				<div className="py-4 sm:py-5">
					{confirmation && (
						<div className="text-green-600 my-5">
							Le mot de passe a bien été modifié.
						</div>
					)}
					<button
						type="button"
						className="rounded-md text-sm font-medium text-primary-600 hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/60 focus:ring-offset-2"
						onClick={toggleEditing}
					>
						Modifier le mot de passe
					</button>
				</div>
			)}
			{isEditing && (
				<div>
					{passwordError && (
						<small className="text-red-500">{passwordError}</small>
					)}
					<div className="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-3 sm:pt-5">
						<dt className="text-sm font-medium text-gray-500">
							Ancien mot de passe
						</dt>
						<dd className="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							<input
								type="password"
								className="flex-grow px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
								value={oldPassword}
								onChange={(e) => setOldPassword(e.target.value)}
							/>
						</dd>
					</div>
					<div className="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-4">
						<button
							type="button"
							className="rounded-md font-medium text-primary-600 hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/60 focus:ring-offset-2 text-sm"
							onClick={toggleEditing}
						>
							Enregistrer
						</button>
					</div>
				</div>
			)}
		</>
	);
}

export default PasswordChanger;
