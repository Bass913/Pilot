import { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import apiService from "../../services/apiService";
import GuestLayout from "../../layouts/GuestLayout";

function Register() {
    const [state, setState] = useState({
        firstname: "",
        lastname: "",
        email: "",
        password: "",
        phone: "",
        error: null,
    });

    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        const firstname = e.target.firstname.value;
        const lastname = e.target.lastname.value;
        const email = e.target.email.value;
        const password = e.target.password.value;
        const phone = e.target.phone.value;
        try {
            const response = await apiService.register({
                firstname: firstname,
                lastname: lastname,
                email: email,
                password: password,
                phone: phone,
            });
            if (response.status === 201) {
                navigate("/auth/login");
            } else {
                const data = response.data;
                setState({ ...state, error: data.detail });
            }
        } catch (error) {
            setState({ ...state, error: error.message });
        }
    };

    return (
        <GuestLayout>
            <section className="w-1/2 mx-auto my-auto">
                <div className="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                    <div className="mx-auto w-full max-w-sm lg:w-96">
                        <div>
                            <h2 className="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
                                Inscrivez-vous
                            </h2>
                            <p className="my-5 text-sm leading-6 text-gray-500 flex">
                                Vous avez déjà un compte ?
                                <NavLink to="/auth/login">
                                    <p className="font-semibold text-primary-500 hover:text-primary-700 ml-2">
                                        Connectez-vous ici
                                    </p>
                                </NavLink>
                            </p>
                        </div>

                        <div>
                            <div className="my-5">
                                {state.error && (
                                    <small className="text-red-600">
                                        {state.error}
                                    </small>
                                )}
                            </div>

                            <div>
                                <form
                                    method="POST"
                                    className="space-y-6 text-left"
                                    onSubmit={handleSubmit}
                                >
                                    <div>
                                        <label
                                            htmlFor="firstname"
                                            className="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            Prénom
                                        </label>
                                        <input
                                            id="firstname"
                                            name="firstname"
                                            type="text"
                                            placeholder="Votre prénom"
                                            className="block w-full pl-2 rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label
                                            htmlFor="lastname"
                                            className="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            Nom
                                        </label>
                                        <input
                                            id="lastname"
                                            name="lastname"
                                            type="text"
                                            placeholder="Votre nom"
                                            className="block w-full pl-2 rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="email"
                                            className="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            Email
                                        </label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            placeholder="exemple@gmail.com"
                                            className="block w-full pl-2 rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label
                                            htmlFor="phone"
                                            className="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            Numéro de téléphone
                                        </label>
                                        <input
                                            id="phone"
                                            name="phone"
                                            type="text"
                                            placeholder="+33543122289"
                                            className="block w-full pl-2 rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="password"
                                            className="block text-sm font-medium leading-6 text-gray-900"
                                        >
                                            Mot de passe
                                        </label>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            placeholder="Mot de passe"
                                            className="block w-full pl-2 rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <button
                                            type="submit"
                                            className="flex w-full justify-center rounded-md bg-primary-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-800 focus-visible-outline focus-visible-outline-2 focus-visible-outline-offset-2 focus-visible-outline-primary-600"
                                        >
                                            S'inscrire
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}

export default Register;
