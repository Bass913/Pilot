import { Outlet } from "react-router-dom";

export default function Root() {
	return (
		<div className="flex flex-col justify-between h-lvh">
			<main>
				<Outlet />
			</main>
		</div>
	);
}
