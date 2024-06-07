import axios from "axios";

const apiClient = axios.create({
	baseURL: "https://localhost",
	headers: {
		"Content-Type": "application/ld+json",
	},
});

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
	createBooking(booking, config) {
		return apiClient.post("/bookings", booking, config);
	},
	getServices() {
		return apiClient.get("/services");
	},
	getSpecialities() {
		return apiClient.get("/specialities");
	},
	register(user) {
		return apiClient.post("/register", user);
	},
	adminCreateUser(user) {
		return apiClient.post("/admin/users", user);
	},
};

export default apiService;
