import BecomeAPartnerLayout from "../layouts/BecomeAPartnerLayout";
import { useTranslation } from "react-i18next";

function BecomeAPartner() {
	const { t } = useTranslation();
	const specialities = [
		"Mécanique générale",
		"Carrosserie",
		"Électricité automobile",
		"Climatisation et chauffage",
		"Diagnostic électronique",
		"Réparation de moteurs diesel",
		"Réparation de pneus",
		"Restauration de voitures anciennes",
		"Tuning et personnalisation",
	];

	const handleSubmit = (e) => {
		e.preventDefault();
		e.preventDefault();
		const formData = new FormData();
		formData.append("firstname", e.target.firstname.value);
		formData.append("lastname", e.target.lastname.value);
		formData.append("postal_code", e.target.postal_code.value);
		formData.append("phone", e.target.phone.value);
		formData.append("speciality", e.target.speciality.value);
		formData.append("email", e.target.email.value);
		formData.append("kbis", e.target.kbis.files[0]);
	};

	return (
		<BecomeAPartnerLayout>
			<form method="POST" onSubmit={handleSubmit} className="lg:min-w-120">
				<h1
					className="text-xl font-normal text-gray-900 text-center mb-8"
					dangerouslySetInnerHTML={{ __html: t("add-company-title") }}
				>
				</h1>
				<div className="flex flex-row gap-5 mt-5">
					<div className="w-1/2">
						<label
							htmlFor="firstname"
							className="block text-sm font-medium text-gray-700"
						>
							{t("firstname")}*
						</label>
						<input
							type="text"
							id="firstname"
							name="firstname"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder={t("enter-your-firstname")}
							required
						/>
					</div>
					<div className="w-1/2">
						<label
							htmlFor="lastname"
							className="block text-sm font-medium text-gray-700"
						>
							{t("lastname")}*
						</label>
						<input
							type="text"
							id="lastname"
							name="lastname"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder={t("enter-your-lastname")}
							required
						/>
					</div>
				</div>
				<div className="flex flex-row gap-5 mt-5">
					<div className="w-1/2">
						<label
							htmlFor="postal_code"
							className="block text-sm font-medium text-gray-700"
						>
							{t("postal-code")}*
						</label>
						<input
							type="text"
							id="postal_code"
							name="postal_code"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder="XXXXX"
							required
						/>
					</div>
					<div className="w-1/2">
						<label
							htmlFor="phone"
							className="block text-sm font-medium text-gray-700"
						>
							{t("phone")}*
						</label>
						<input
							type="text"
							id="phone"
							name="phone"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder="06XXXXXXXX"
							required
						/>
					</div>
				</div>
				<div className="mt-5">
					<label
						htmlFor="speciality"
						className="block text-sm font-medium text-gray-700"
					>
						{t("speciality")}*
					</label>
					<select
						id="speciality"
						name="speciality"
						className="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
					>
						{specialities.map((speciality) => (
							<option key={speciality}>{speciality}</option>
						))}
					</select>
				</div>
				<div className="mt-5">
					<label
						htmlFor="email"
						className="block text-sm font-medium text-gray-700"
					>
						{t("email")}*
					</label>
					<input
						type="email"
						id="email"
						name="email"
						className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
						placeholder={t("enter-your-email")}
						required
					/>
				</div>
				<div className="mt-5">
					<label
						htmlFor="kbis"
						className="block text-sm font-medium text-gray-700"
					>
						{t("kbis")}*
					</label>
					<input
						type="file"
						id="kbis"
						name="kbis"
						className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
						accept=".pdf,.doc,.docx"
						placeholder={t("no-file-selected")}
						required
					/>
					<small className="text-gray-500 mt-2">
						{t("kbis-file-description")}
					</small>
				</div>
				<small className="text-gray-500 mt-5">
					* {t("required-fields")}
				</small>
				<button
					type="submit"
					className="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-normal leading-6 text-white shadow-sm hover:bg-primary-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 mt-8"
				>
					{t("send-request")}
				</button>
			</form>
		</BecomeAPartnerLayout>
	);
}

export default BecomeAPartner;
