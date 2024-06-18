import axios from "axios";

const apiClient = axios.create({
	baseURL: "https://localhost",
	headers: {
		"Content-Type": "application/ld+json",
	},
});

apiClient.interceptors.request.use(
	(config) => {
		if (config.method === "patch") {
			config.headers["Content-Type"] = "application/merge-patch+json";
		}
		if (config.url === "/requests") {
			config.headers["Content-Type"] = "multipart/form-data";
		}
		return config;
	},
	(error) => {
		return Promise.reject(error);
	}
);



apiClient.interceptors.response.use(
	(response) => response,
	(error) => {
		console.error("API call error:", error);
		return Promise.reject(error);
	}
);

const apiService = {
	getCompanies({ search = null, page = 1 }) {
		const url = search
			? `/companies?search=${search.trim()}&page=${page}`
			: "/companies?page=" + page;
		return apiClient.get(url);
	},
	getCompany(id) {
		return apiClient.get(`/companies/${id}`);
	},
	getServices({ page = 1 }) {
		return apiClient.get(`/services?page=${page}`);
	},
	getCompanyServices({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/services?page=${page}`);
	},
	getUsers({ page = 1 }) {
		return apiClient.get(`/users?page=${page}`);
	},
	getBookings({ page = 1 }) {
		return apiClient.get(`/bookings?page=${page}`);
	},
	getBooking(id) {
		return apiClient.get(`/bookings/${id}`);
	},
	getUserBookings(userId) {
		return apiClient.get(`/users/${userId}/bookings`);
	},
	updateBooking(bookingId, booking) {
		return apiClient.patch(bookingId, booking);
	},
	createBooking(booking, config) {
		return apiClient.post("/bookings", booking, config);
	},
	getServices() {
		return apiClient.get("/services");
	},
	getSpecialities() {
		return apiClient.get("/specialities");
	},
	getReviewCategories() {
		return apiClient.get("/category_reviews");
	},
	createRating(rating) {
		return apiClient.post("/ratings", rating);
	},
	createReview(review) {
		return apiClient.post("/reviews", review);
	},
	register(user) {
		return apiClient.post("/register", user);
	},
	adminCreateUser(user) {
		return apiClient.post("/admin/users", user);
	},
	createRequest(request) {
		return apiClient.post("/requests", request);
	}
};

export default apiService;
