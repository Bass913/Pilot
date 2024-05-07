import { useUser } from "../hooks/useUser";

function ProviderReservation() {
	const { serviceSelected } = useUser();
	console.log(serviceSelected);

	return (
		<div>
			<h1>Provider Reservation Page</h1>
		</div>
	);
}

export default ProviderReservation;
