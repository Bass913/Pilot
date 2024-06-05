// UnauthorizedPage component

import React from "react";
import { useTranslation } from "react-i18next";
import { Link } from "react-router-dom";
import DefaultLayout from "../../layouts/DefaultLayout";

const UnauthorizedPage = () => {
	const { t } = useTranslation();

	return (
		<DefaultLayout>
			<div
				className="container text-center mx-auto flex flex-col justify-center items-center"
				style={{ minHeight: "calc(100vh - 15rem)" }}
			>
				<h1 className="text-3xl font-bold text-primary-700 mb-5">
					{t("error.unauthorized.title")}
				</h1>
				<p className="text-lg">{t("error.unauthorized.message")}</p>
				<Link className="text-primary-500 mt-5 hover:underline" to="/">
					{t("error.unauthorized.back")}
				</Link>
			</div>
		</DefaultLayout>
	);
};

export default UnauthorizedPage;
