import { useNavigate } from "react-router-dom";
import { useUser } from "../hooks/useUser";

function ServicesChooser({ services }) {
	const navigate = useNavigate();
	const { setServiceSelected } = useUser();

	const handleServiceSelection = (service) => {
		setServiceSelected(service);
		navigate("reservation");
	};

	return (
		<ul className="text-gray-600 mt-2 font-light bg-white px-8 rounded-md shadow-md">
			{services.map((service, index) => (
				<li
					key={index}
					className={`block py-4 text-base flex justify-between items-center ${index !== services.length - 1 && "border-b border-gray-200"}`}
				>
					<p className="font-normal">{service.name}</p>
					<div className="flex gap-6 items-center">
						<p className="font-normal">
							{service.price} €
							<small className="text-gray-400">*</small>
						</p>
						<button
							className="bg-gray-900 text-white px-3 py-1.5 rounded-sm text-sm hover:bg-gray-700 font-normal text-sm"
							onClick={() => handleServiceSelection(service)}
						>
							Choisir
						</button>
					</div>
				</li>
			))}
		</ul>
	);
}

export default ServicesChooser;
