import { useState, useEffect } from "react";
import {
	Dialog,
	DialogPanel,
	TransitionChild,
	Transition as TransitionRoot,
} from "@headlessui/react";
import {
	Bars3Icon,
	UserIcon,
	XMarkIcon,
	ArrowRightStartOnRectangleIcon,
	BellAlertIcon,
	HomeIcon,
	UserGroupIcon,
	ChevronDoubleRightIcon,
	ChevronDoubleLeftIcon,
	CalendarDaysIcon,
	WrenchScrewdriverIcon,
	BuildingStorefrontIcon,
} from "@heroicons/react/24/outline";
import { Link, useLocation } from "react-router-dom";
import { useUser } from "../hooks/useUser";
import { useNavigate } from "react-router-dom";

const DashboardLayout = ({ children }) => {
	const location = useLocation();
	const { user, logout, sidebarLarge, setSidebarLarge } = useUser();
	const navigate = useNavigate();
	const [navigation, setNavigation] = useState([
		{
			name: "Accueil",
			href: "/admin",
			icon: HomeIcon,
			current: location.pathname === "/admin",
		},
		{
			name: "Etablissements",
			href: "/admin/providers",
			icon: BuildingStorefrontIcon,
			current: location.pathname === "/admin/providers",
		},
		{
			name: "Prestations",
			href: "/admin/services",
			icon: WrenchScrewdriverIcon,
			current: location.pathname === "/admin/services",
		},
		{
			name: "Employés",
			href: "/admin/employees",
			icon: UserGroupIcon,
			current: location.pathname === "/admin/employees",
		},
		{
			name: "Planning",
			href: "/admin/schedule",
			icon: CalendarDaysIcon,
			current: location.pathname === "/admin/schedule",
		},
	]);

	const [sidebarOpen, setSidebarOpen] = useState(false);

	const logoutFromDashboard = () => {
		logout();
		navigate("/auth/login");
	};

	useEffect(() => {
		if (!sidebarLarge) {
			setSidebarLarge(false);
		}
	}, []);

	const toggleLargeSidebar = () => {
		setSidebarLarge((prevLarge) => !prevLarge);
	};

	return (
		<div>
			{/* Mobile menu, show/hide based on menu open state. */}
			<TransitionRoot show={sidebarOpen}>
				<Dialog
					as="div"
					className="relative z-50 lg:hidden"
					onClose={() => setSidebarOpen(false)}
				>
					<TransitionChild
						enter="transition-opacity ease-linear duration-300"
						enterFrom="opacity-0"
						enterTo="opacity-100"
						leave="transition-opacity ease-linear duration-300"
						leaveFrom="opacity-100"
						leaveTo="opacity-0"
					>
						<div className="fixed inset-0 bg-primary/80" />
					</TransitionChild>

					<div className="fixed inset-0 flex">
						<TransitionChild
							enter="transition ease-in-out duration-300 transform"
							enterFrom="-translate-x-full"
							enterTo="translate-x-0"
							leave="transition ease-in-out duration-300 transform"
							leaveFrom="translate-x-0"
							leaveTo="-translate-x-full"
						>
							<DialogPanel className="relative mr-16 flex w-full max-w-xs flex-1">
								<TransitionChild
									enter="ease-in-out duration-300"
									enterFrom="opacity-0"
									enterTo="opacity-100"
									leave="ease-in-out duration-300"
									leaveFrom="opacity-100"
									leaveTo="opacity-0"
								>
									<div className="absolute left-full top-0 flex w-16 justify-center pt-5">
										<button
											type="button"
											className="-m-2.5 p-2.5"
											onClick={() =>
												setSidebarOpen(false)
											}
										>
											<span className="sr-only">
												Close sidebar
											</span>
											<XMarkIcon
												className="h-6 w-6 text-white"
												aria-hidden="true"
											/>
										</button>
									</div>
								</TransitionChild>

								{/* Sidebar component, swap this element with another sidebar if you like */}
								<div className="flex-grow flex flex-col gap-y-5 overflow-y-auto px-6 pb-2 ring-1 ring-white/10 bg-gray-50 relative z-10">
									<XMarkIcon
										className="h-6 w-6 absolute right-5 top-5 cursor-pointer hover:bg-gray-100"
										onClick={() => setSidebarOpen(false)}
										aria-hidden="true"
									/>
									<Link
										to="/"
										className="flex h-16 shrink-0 items-center mt-4"
									/>

									<nav className="flex flex-1 flex-col">
										<ul
											role="list"
											className="flex flex-1 flex-col gap-y-7"
										>
											<li>
												<ul
													role="list"
													className="-mx-2 space-y-1"
												>
													{navigation.map((item) => (
														<li key={item.name}>
															<Link
																to={item.href}
																className={`group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ${
																	item.current
																		? "text-gray-500"
																		: "hover:bg-gray-100"
																}`}
															>
																<item.icon
																	className="h-6 w-6 shrink-0"
																	aria-hidden="true"
																/>
																{item.name}
															</Link>
														</li>
													))}
												</ul>
											</li>
										</ul>
									</nav>
								</div>
							</DialogPanel>
						</TransitionChild>
					</div>
				</Dialog>
			</TransitionRoot>

			{/* Static sidebar for desktop */}
			<div
				className={`hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col bg-gray-50/30 lg:border-r lg:border-white/10 lg:shadow-lg lg:top-0 lg:bottom-0 lg:overflow-y-auto lg:transition-all lg:duration-300 ${!sidebarLarge && "lg:w-auto"}`}
			>
				<div className="flex-grow flex flex-col gap-y-5 overflow-y-auto px-6">
					<div>
						<Link
							to="/"
							className={`flex shrink-0 items-center mt-5 ${!sidebarLarge ? "h-0" : "h-16"}`}
						>
							<img
								className={`h-auto w-10 mr-2 ${!sidebarLarge && "hidden"}`}
								src="/logo.svg"
								alt="Logo"
							/>
							<h1
								className={`text-2xl font-bold text-primary-600 ${!sidebarLarge && "hidden"}`}
							>
								Pilot
							</h1>
						</Link>
						{sidebarLarge ? (
							<ChevronDoubleLeftIcon
								className="h-6 w-6 absolute right-5 top-5 cursor-pointer bg-gray-100 p-1 rounded-md hover:bg-gray-200"
								onClick={toggleLargeSidebar}
								aria-hidden="true"
							/>
						) : (
							<ChevronDoubleRightIcon
								className="h-7 w-7 cursor-pointer bg-gray-100 p-1 rounded-md hover:bg-gray-200"
								onClick={toggleLargeSidebar}
								aria-hidden="true"
							/>
						)}
						<hr className={sidebarLarge ? "mt-2" : "mt-5"} />
					</div>
					<nav className="flex flex-1 flex-col">
						<ul
							role="list"
							className="flex flex-1 flex-col gap-y-7"
						>
							<li>
								<ul role="list" className="-mx-2 space-y-1">
									{navigation.map((item) => (
										<li key={item.name}>
											<Link
												to={item.href}
												className={`group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold ${
													item.current
														? "text-primary-700"
														: "hover:bg-gray-100"
												}`}
											>
												<item.icon
													className="h-6 w-6 shrink-0"
													aria-hidden="true"
												/>
												{!sidebarLarge ? "" : item.name}
											</Link>
										</li>
									))}
								</ul>
							</li>
							<li className="mt-auto -mx-2">
								<hr className="mx-2 mb-3" />
								<Link
									to="/dash/profile"
									className={[
										location.pathname === "/dash/profile"
											? "text-primary-700"
											: "hover:bg-gray-100 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold",
									]}
								>
									<UserIcon
										className="h-6 w-6 shrink-0"
										aria-hidden="true"
									/>
									{!sidebarLarge
										? ""
										: user
											? user.firstname +
												" " +
												user.lastname
											: "Votre profil"}
								</Link>
								<button
									type="button"
									onClick={logoutFromDashboard}
									className="mb-5 cursor-pointer hover:bg-gray-100 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold w-full"
								>
									<ArrowRightStartOnRectangleIcon
										className="h-6 w-6 shrink-0"
										aria-hidden="true"
									/>
									{!sidebarLarge ? "" : "Déconnexion"}
								</button>
							</li>
						</ul>
					</nav>
				</div>
			</div>

			<div className="sticky top-0 z-40 flex items-center gap-x-6 px-4 py-4 shadow-sm sm:px-6 lg:hidden">
				<button
					type="button"
					className="-m-2.5 p-2.5 lg:hidden"
					onClick={() => setSidebarOpen(true)}
				>
					<span className="sr-only">Ouvrir la barre</span>
					<Bars3Icon className="h-6 w-6" aria-hidden="true" />
				</button>
				<Link to="/dash/profile">
					<span className="sr-only">Votre profil</span>
					<UserIcon
						className="h-6 w-6 shrink-0 text-white"
						aria-hidden="true"
					/>
				</Link>
			</div>

			<main
				className={`lg:pl-20 ${!sidebarLarge ? "lg:pl-0" : "lg:pl-64"}`}
			>
				{children}
			</main>
		</div>
	);
};

export default DashboardLayout;
