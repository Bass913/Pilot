import React from "react";
import { InformationCircleIcon } from "@heroicons/react/24/outline";
import { useNavigate } from "react-router-dom";

function Alert({ onClose }) {

	const navigate = useNavigate();

	return (
		<div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
			<div className="bg-white p-6 rounded-lg shadow-md flex flex-col gap-6">
				<div className="text-gray-800 flex items-center gap-2">
					<InformationCircleIcon className="w-6 h-6 text-orange-600 inline-block" />
					<h2 className="text-lg font-medium">Information</h2>
				</div>
				<p className="text-gray-600 font-light">
					Vous n'êtes pas connecté. Veuillez vous connecter pour
					continuer.
				</p>
				<div className="flex justify-end mt-5">
					<button
						className="px-4 py-2 bg-primary-700 text-white rounded hover:bg-primary-600 mr-2 text-sm"
						onClick={() => {
                            navigate("/auth/login");
							onClose();
						}}
					>
						Se connecter
					</button>
					<button
						className="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 text-sm"
						onClick={onClose}
					>
						Annuler
					</button>
				</div>
			</div>
		</div>
	);
}

export default Alert;
