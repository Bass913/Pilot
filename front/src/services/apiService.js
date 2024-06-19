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
	// Companies
	getCompanies({ search = null, page = 1 }) {
		const url = search
			? `/companies?search=${search.trim()}&page=${page}`
			: "/companies?page=" + page;
		return apiClient.get(url);
	},
	getCompany(id) {
		return apiClient.get(`/companies/${id}`);
	},

	// Services
	getServices({ page = 1 }) {
		return apiClient.get(`/services?page=${page}`);
	},
	getCompanyServices({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/services?page=${page}`);
	},

	// Users
	getUsers({ page = 1 }) {
		return apiClient.get(`/users?page=${page}`);
	},
	adminCreateUser(user) {
		return apiClient.post("/admin/users", user);
	},
	getCompanyEmployees({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/employees?page=${page}`);
	},

	// Bookings
	getBookings({ page = 1 }) {
		return apiClient.get(`/bookings?page=${page}`);
	},
	getCompanyBookings({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/bookings?page=${page}`);
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

	// Specialities
	getSpecialities() {
		return apiClient.get("/specialities");
	},

	// Categories
	getReviewCategories() {
		return apiClient.get("/category_reviews");
	},

	// Reviews / Ratings
	createRating(rating) {
		return apiClient.post("/ratings", rating);
	},
	createReview(review) {
		return apiClient.post("/reviews", review);
	},

	// Auth
	register(user) {
		return apiClient.post("/register", user);
	},
	getMe() {
		return apiClient.get("/me");
	},

	// Requests
	getRequests({ page = 1 }) {
		return apiClient.get("/requests?page=" + page);
	},
	createRequest(request) {
		return apiClient.post("/requests", request);
	},

	// Schedule
	getCompanyEmployeesSchedule(companyId) {
		return apiClient.get(`/companies/${companyId}/employees/planning`);
	},
};

export default apiService;
