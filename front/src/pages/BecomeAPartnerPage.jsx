import BecomeAPartnerLayout from "../layouts/BecomeAPartnerLayout";

function BecomeAPartner() {
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
		const firstname = e.target.firstname.value;
		const lastname = e.target.lastname.value;
		const postal_code = e.target.postal_code.value;
		const phone = e.target.phone.value;
		const speciality = e.target.speciality.value;
		const email = e.target.email.value;
		console.log({
			firstname,
			lastname,
			postal_code,
			phone,
			speciality,
			email,
		});
	};

	return (
		<BecomeAPartnerLayout>
			<form method="POST" onSubmit={handleSubmit}>
				<h1 className="text-xl font-normal text-gray-900 text-center mb-8">
					Vous êtes un professionnel de l'automobile ? <br />
					Faites votre demande pour devenir notre partenaire
				</h1>
				<div className="flex flex-row gap-5 mt-5">
					<div className="w-1/2">
						<label
							htmlFor="firstname"
							className="block text-sm font-medium text-gray-700"
						>
							Prénom*
						</label>
						<input
							type="text"
							id="firstname"
							name="firstname"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder="Entrer votre prénom"
							required
						/>
					</div>
					<div className="w-1/2">
						<label
							htmlFor="lastname"
							className="block text-sm font-medium text-gray-700"
						>
							Nom*
						</label>
						<input
							type="text"
							id="lastname"
							name="lastname"
							className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
							placeholder="Entrer votre nom"
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
							Code postal*
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
							Téléphone*
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
						Spécialité*
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
						Email*
					</label>
					<input
						type="email"
						id="email"
						name="email"
						className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
						placeholder="Entrer votre email"
						required
					/>
				</div>
				<small className="text-gray-500 mt-2">
					* Champs obligatoires
				</small>
				<button
					type="submit"
					className="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-normal leading-6 text-white shadow-sm hover:bg-primary-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 mt-8"
				>
					Envoyer ma demande
				</button>
			</form>
		</BecomeAPartnerLayout>
	);
}

export default BecomeAPartner;
