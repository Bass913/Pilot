import {
    ArrowLeftEndOnRectangleIcon,
    ArrowRightStartOnRectangleIcon,
} from "@heroicons/react/24/outline";
import { NavLink } from "react-router-dom";
import Button from "./Button";
import { UserCircleIcon } from "@heroicons/react/24/solid";
import { useUser } from "../hooks/useUser";
import { useTranslation } from "react-i18next";

export default function Header({ user }) {
    const { t, i18n } = useTranslation();
    const { logout, language, setLanguage } = useUser();

    const changeLanguage = () => {
        const newLanguage = language === "fr" ? "en" : "fr";
        i18n.changeLanguage(newLanguage);
        setLanguage(newLanguage);
    };

    const hasAccessToAdmin =
        user &&
        user.roles &&
        (user.roles.includes("ROLE_EMPLOYEE") ||
            user.roles.includes("ROLE_ADMIN") ||
            user.roles.includes("ROLE_SUPERADMIN"));

    return (
        <header className="h-20 flex items-center justify-between px-5 mx-auto fixed top-0 left-0 right-0 z-50 bg-white border-gray-200">
            <div>
                <NavLink to="/">
                    <img src="/logo.svg" alt="logo" className="h-10" />
                </NavLink>
            </div>

            <div className="relative flex items-center gap-5 group">
                <nav className="flex gap-5">
                    <Button
                        className="hover:bg-gray-100 p-4 rounded text-sm flex items-center gap-2 text-gray-800"
                        onClick={changeLanguage}
                    >
                        <img
                            src={`/flags/${language}.svg`}
                            alt={language}
                            className="w-5 h-5"
                        />
                    </Button>

                    {user && user.roles ? (
                        <>
                            <NavLink to="/profile">
                                <Button className="hover:bg-gray-100 p-4 rounded text-sm flex items-center gap-2 text-gray-800">
                                    <UserCircleIcon className="w-5 h-5" />
                                    {user.firstname} {user.lastname}
                                </Button>
                            </NavLink>
                            {hasAccessToAdmin && (
                                <NavLink to="/admin">
                                    <Button className="bg-gray-200 hover:bg-gray-300 p-4 rounded text-sm flex items-center gap-2 text-gray-800">
                                        {t("dashboard")}
                                    </Button>
                                </NavLink>
                            )}
                            <Button
                                className="text-white bg-primary-600 hover:bg-primary-600 p-4 rounded text-sm flex items-center gap-2 hover:bg-primary-700"
                                onClick={logout}
                            >
                                <ArrowRightStartOnRectangleIcon className="w-5 h-5" />
                                {t("logout")}
                            </Button>
                        </>
                    ) : (
                        <>
                            <NavLink to="/auth/login">
                                <Button className="text-white bg-primary-600 hover:bg-primary-600 p-4 rounded text-sm flex items-center gap-2 hover:bg-primary-700">
                                    <ArrowLeftEndOnRectangleIcon className="w-5 h-5" />
                                    {t("login")}
                                </Button>
                            </NavLink>
                            <NavLink to="/become-a-partner">
                                <Button className="hover:bg-gray-200 p-4 rounded text-sm flex items-center gap-2 text-gray-800 bg-gray-100">
                                    {t("add-your-company")}
                                </Button>
                            </NavLink>
                        </>
                    )}
                </nav>
            </div>
        </header>
    );
}
