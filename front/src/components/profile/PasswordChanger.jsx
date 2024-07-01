import React, { useState } from "react";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";
import { ToastContainer, toast } from "react-toastify";

function PasswordChanger({ userId }) {
    const { t } = useTranslation();
    const [isEditing, setIsEditing] = useState(false);
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
            if (!newPassword.length || !confirmPassword.length) {
                setPasswordError("Veuillez remplir tous les champs.");
                return;
            }
            try {
                const response = await apiService.updatePassword(userId, {
                    password: newPassword,
                });

                setIsEditing(false);
                setConfirmation(true);

                if (response.status === 200) {
                    toast.success("Le mot de passe a été changée avec succès");
                }
            } catch (error) {
                setPasswordError("Une erreur est survenue.", error);
            }
        } else {
            setIsEditing(true);
        }
    };

    return (
        <>
            <ToastContainer
                position="top-right"
                autoClose={5000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
                transition:Bounce
            />
            {!isEditing && (
                <div className="py-4 sm:py-5">
                    {confirmation && (
                        <div className="text-green-600 my-5">
                            {confirmation}
                        </div>
                    )}
                    <button
                        type="button"
                        className="rounded-md text-sm font-medium text-primary-600 hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600/60 focus:ring-offset-2"
                        onClick={toggleEditing}
                    >
                        {t("edit-password")}
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
                            {t("new-password")}
                        </dt>
                        <dd className="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input
                                type="password"
                                className="flex-grow px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                value={newPassword}
                                onChange={(e) => setNewPassword(e.target.value)}
                            />
                        </dd>
                    </div>
                    <div className="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-3 sm:pt-5">
                        <dt className="text-sm font-medium text-gray-500">
                            {t("confirm-password")}
                        </dt>
                        <dd className="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input
                                type="password"
                                className="flex-grow px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                value={confirmPassword}
                                onChange={(e) =>
                                    setConfirmPassword(e.target.value)
                                }
                            />
                        </dd>
                    </div>
                    <div className="flex justify-center items-center pt-10">
                        <button
                            type="button"
                            className="text-white bg-primary-600 hover:bg-primary-600 p-4 rounded text-sm flex items-center gap-2 hover:bg-primary-700"
                            onClick={toggleEditing}
                        >
                            {t("save")}
                        </button>
                    </div>
                </div>
            )}
        </>
    );
}

export default PasswordChanger;
