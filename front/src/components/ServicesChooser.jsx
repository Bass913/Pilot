import { useNavigate } from "react-router-dom";
import { useUser } from "../hooks/useUser";
import { useTranslation } from "react-i18next";

const formatPrice = (price) => {
	return new Intl.NumberFormat("fr-FR", {
		style: "currency",
		currency: "EUR",
	}).format(price);
};

function ServicesChooser({ services }) {
	const { t } = useTranslation();
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
					<p className="font-normal">{t(service.service.name)}</p>
					<div className="flex gap-6 items-center">
						<p className="font-normal">
							{formatPrice(service.price)}
							<small className="text-gray-400">*</small>
						</p>
						<button
							className="bg-primary-900 text-white px-3 py-1.5 rounded-sm text-sm hover:bg-primary-800 font-normal text-sm"
							onClick={() => handleServiceSelection(service)}
						>
							{t("choose")}
						</button>
					</div>
				</li>
			))}
		</ul>
	);
}

export default ServicesChooser;
