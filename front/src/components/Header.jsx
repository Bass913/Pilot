import {
	ArrowLeftEndOnRectangleIcon,
	ArrowRightStartOnRectangleIcon,
} from "@heroicons/react/24/outline";
import {
	HeartIcon as SolidHeartIcon,
	FaceFrownIcon,
} from "@heroicons/react/24/solid";
import { NavLink } from "react-router-dom";
import Button from "./Button";
import { UserCircleIcon } from "@heroicons/react/24/solid";
import { useUser } from "../hooks/useUser";

export default function Header({ user }) {
	const { logout } = useUser();
	return (
		<header className="h-20 flex items-center justify-between px-5 mx-auto fixed top-0 left-0 right-0 z-50 bg-white border-gray-200">
			<div>
				<NavLink to="/">
					<img src="/logo.svg" alt="logo" className="h-10" />
				</NavLink>
			</div>
			{user ? (
				<div className="relative flex items-center gap-5 group">
					<nav className="flex gap-5">
						<Button className="hover:bg-gray-100 p-4 rounded text-sm flex items-center gap-2 text-gray-800">
							<UserCircleIcon className="w-5 h-5" />
							{user.firstname} {user.lastname}
						</Button>
						<div className="hidden group-hover:block absolute p-2 top-full left-0 bg-white flex flex-col gap-5 border border-gray-200 rounded shadow-md">
							<div className="w-0 h-0 border-l-[15px] border-l-transparent border-b-[20px] border-b-white border-r-[15px] border-r-transparent absolute top-0 left-5 transform -translate-y-5"></div>
							<NavLink
								to="/profile/favorite-recipes"
								className="text-gray-800 hover:bg-gray-200 p-3 flex gap-2 text-sm items-center font-medium rounded"
							>
								<SolidHeartIcon className=" text-primary-500 w-4" />
								Mes prestataires
							</NavLink>
							<NavLink
								to="/profile/preferences"
								className="text-gray-800 hover:bg-gray-200 p-3 flex items-center gap-2 text-sm font-medium rounded"
							>
								<FaceFrownIcon className=" text-primary-500 w-4" />
								Autre
							</NavLink>
						</div>
						<Button
							className="text-white bg-primary-600 hover:bg-primary-600 p-4 rounded text-sm flex items-center gap-2 hover:bg-primary-700"
							onClick={logout}
						>
							<ArrowRightStartOnRectangleIcon className="w-5 h-5" />
							Se déconnecter
						</Button>
					</nav>
				</div>
			) : (
				<nav className="flex gap-5">
					<NavLink to="/auth/login">
						<Button className="text-white bg-primary-600 hover:bg-primary-600 p-4 rounded text-sm flex items-center gap-2 hover:bg-primary-700">
							<ArrowLeftEndOnRectangleIcon className="w-5 h-5" />
							Se connecter
						</Button>
					</NavLink>
					<NavLink to="/become-a-partner">
						<Button className="hover:bg-gray-200 p-4 rounded text-sm flex items-center gap-2 text-gray-800 bg-gray-100">
							Ajoutez votre établissement
						</Button>
					</NavLink>
				</nav>
			)}
		</header>
	);
}
