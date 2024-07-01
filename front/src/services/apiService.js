import axios from "axios";

const apiClient = axios.create({
	baseURL: import.meta.env.VITE_API_URL,
	headers: {
		"Content-Type": "application/ld+json",
		"Authorization": `Bearer ${localStorage.getItem("token") ? localStorage.getItem("token").replace(/"/g, "") : ""}`,
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
	getCompanies({ search = null, city = null, page = 1, pagination = true}) {
		const params = new URLSearchParams();
		if (search) {
			params.append('search', search.trim());
		}
		if (city) {
			params.append('city', city.trim());
		}
		if (!pagination) {
			params.append('pagination', pagination);
		}
		params.append('page', page);
	
		const url = `/companies?${params.toString()}`;
		return apiClient.get(url);
	},
	
	getCompany(id) {
		return apiClient.get(`/companies/${id}`);
	},
	getAdminCompanies(id) {
		return apiClient.get(`/users/${id}/companies`);
	},
	updateCompany(id, company) {
		return apiClient.patch(`/companies/${id}`, company);
	},
	removeCompany(id) {
		return apiClient.delete(`/companies/${id}`);
	},

	// Services
	getServices({ pagination = true, page = 1 }) {
		return apiClient.get(`/services?page=${page}&pagination=${pagination}`);
	},
	getService(id) {
		return apiClient.get(`/services/${id}`);
	},
	getCompanyServices({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/services?page=${page}`);
	},
	updateService(id, service) {
		return apiClient.patch(`/services/${id}`, service);
	},
	removeService(id) {
		return apiClient.delete(`/services/${id}`);
	},

	// Company Services
	getCompanyService(id) {
		return apiClient.get(`/company_services/${id}`);
	},
	updateCompanyService(id, service) {
		return apiClient.patch(`/company_services/${id}`, service);
	},
	removeCompanyService(id) {
		return apiClient.delete(`/company_services/${id}`);
	},

	// Users
	getUsers({ page = 1 }) {
		return apiClient.get(`/users?page=${page}`);
	},
	getUser(id) {
		return apiClient.get(`/users/${id}`);
	},
	adminCreateUser(user) {
		return apiClient.post("/admin/users", user);
	},
	getCompanyEmployees({ companyId, page = 1 }) {
		return apiClient.get(`/companies/${companyId}/employees?page=${page}`);
	},
	updateUser(id, user) {
		return apiClient.patch(`/api/users/${id}`, user);
	},
	removeUser(id) {
		return apiClient.delete(`/api/users/${id}`);
	},
	updatePassword(id, password) {
		return apiClient.patch(`/api/users/password/${id}`, password);
	},

	// Bookings
	getBookings({ page = 1 }) {
		return apiClient.get(`/api/bookings?page=${page}`);
	},
	getCompanyBookings({ companyId, page = 1, pagination = true }) {
		return apiClient.get(
			`/api/companies/${companyId}/bookings?page=${page}&pagination=${pagination}`
		);
	},
	getBooking(id) {
		return apiClient.get(`/api/bookings/${id}`);
	},
	getUserBookings(userId) {
		return apiClient.get(`/api/client/${userId}/bookings`);
	},
	updateBooking(bookingId, booking) {
		return apiClient.patch(`/api/bookings/${bookingId}`, booking);
	},
	createBooking(booking, config) {
		return apiClient.post("/api/bookings", booking, config);
	},
	removeBooking(bookingId) {
		return apiClient.delete(`/api/bookings/${bookingId}`);
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
		return apiClient.post("/api/reviews", review);
	},

	// Auth
	register(user) {
		return apiClient.post("/register", user);
	},
	getMe() {
		return apiClient.get("/api/me");
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
	getCompanySchedule(companyId) {
		return apiClient.get(`/companies/${companyId}/planning`);
	},

	// Statistics
	getSuperAdminStatistics() {
		return apiClient.get("/api/stats/super_admin");
	},
	getAdminStatistics(id) {
		return apiClient.get(`/api/stats/admin/${id}`);
	},
};

export default apiService;
