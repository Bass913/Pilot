import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import apiService from "../../services/apiService";

const AddFeedbackModal = ({ isOpen, onClose, onSubmit, company }) => {
	const { t } = useTranslation();
	const [feedback, setFeedback] = useState("");
	const [reviewCategories, setReviewCategories] = useState([]);
	const [ratings, setRatings] = useState({});
	const [hoveredRating, setHoveredRating] = useState({});

	const fetchServices = async () => {
		const response = await apiService.getReviewCategories();
		setReviewCategories(response.data["hydra:member"]);
	};

	useEffect(() => {
		fetchServices();
	}, []);

	const handleRatingChange = (serviceId, rating) => {
		setRatings({ ...ratings, [serviceId]: rating });
	};

	const handleMouseEnter = (serviceId, rating) => {
		setHoveredRating({ ...hoveredRating, [serviceId]: rating });
	};

	const handleMouseLeave = (serviceId) => {
		setHoveredRating({ ...hoveredRating, [serviceId]: 0 });
	};

	const handleSubmit = (e) => {
		e.preventDefault();
		onSubmit({ feedback, ratings });
		setFeedback("");
		setRatings({});
		onClose();
	};

	return (
		<div
			className={`fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50 ${isOpen ? "" : "hidden"}`}
		>
			<div className="flex items-center justify-center min-h-screen px-4">
				<div className="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full md:w-132">
					<div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 w-full">
						<h3 className="text-lg leading-6 font-medium text-gray-900 mb-5">
							{t("add-feedback-to")} {company?.name}
						</h3>
						<form onSubmit={handleSubmit}>
							<div className="mt-2">
								<textarea
									className="w-full border border-gray-300 rounded-md p-2"
									value={feedback}
									onChange={(e) =>
										setFeedback(e.target.value)
									}
									rows="5"
									placeholder={t("enter-your-feedback")}
								/>
							</div>
							<div className="mt-4">
								{reviewCategories.map((service) => (
									<div key={service.id} className="mt-2">
										<label className="block text-sm font-medium text-gray-700">
											{service.name}
										</label>
										<div className="flex space-x-1 mt-1">
											{[1, 2, 3, 4, 5].map((star) => (
												<button
													type="button"
													key={star}
													className={`text-xl ${
														hoveredRating[
															service.id
														]
															? hoveredRating[
																	service.id
																] >= star
																? "text-primary-400"
																: "text-gray-300"
															: ratings[
																		service
																			.id
																  ] >= star
																? "text-primary-500"
																: "text-gray-300"
													}`}
													onClick={() =>
														handleRatingChange(
															service.id,
															star
														)
													}
													onMouseEnter={() =>
														handleMouseEnter(
															service.id,
															star
														)
													}
													onMouseLeave={() =>
														handleMouseLeave(
															service.id
														)
													}
												>
													★
												</button>
											))}
										</div>
									</div>
								))}
							</div>
							<div className="mt-5 sm:mt-6">
								<button
									type="submit"
									className="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
								>
									{t("submit")}
								</button>
							</div>
						</form>
					</div>
					<div className="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
						<button
							type="button"
							className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
							onClick={onClose}
						>
							{t("cancel")}
						</button>
					</div>
				</div>
			</div>
		</div>
	);
};

export default AddFeedbackModal;
