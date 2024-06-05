import axios from "axios";

const apiClient = axios.create({
	baseURL: "https://localhost",
	headers: {
		"Content-Type": "application/json",
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
	getUsers({ page = 1 }) {
		return apiClient.get(`/users?page=${page}`);
	},
};

export default apiService;
