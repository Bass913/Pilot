import axios from "axios";

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Content-Type": "application/ld+json",
    Authorization: `Bearer ${localStorage.getItem("token") ? localStorage.getItem("token").replace(/"/g, "") : ""}`,
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
  },
);

apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("API call error:", error);
    return Promise.reject(error);
  },
);

const apiService = {
  // Companies
  getCompanies({ search = null, city = null, page = 1, pagination = true }) {
    const params = new URLSearchParams();
    if (search) {
      params.append("search", search.trim());
    }
    if (city) {
      params.append("city", city.trim());
    }
    if (!pagination) {
      params.append("pagination", pagination);
    }
    params.append("page", page);

    const url = `/companies?${params.toString()}`;
    return apiClient.get(url);
  },
  getCompany(id) {
    return apiClient.get(`/companies/${id}`);
  },
  getAdminCompanies(id) {
    return apiClient.get(`/api/users/${id}/companies`);
  },
  createCompany(company) {
    return apiClient.post(`api/companies`, company);
  },
  updateCompany(id, company) {
    return apiClient.patch(`/api/companies/${id}`, company);
  },
  removeCompany(id) {
    return apiClient.delete(`/api/companies/${id}`);
  },

  // Services
  getServices({ pagination = true, page = 1 }) {
    return apiClient.get(`/services?page=${page}&pagination=${pagination}`);
  },
  getService(id) {
    return apiClient.get(`/services/${id}`);
  },
  createService(service) {
    return apiClient.post(`/services`, service);
  },
  getCompanyServices({ companyId, page = 1 }) {
    return apiClient.get(`/companies/${companyId}/services?page=${page}`);
  },
  updateService(id, service) {
    return apiClient.patch(`/api/services/${id}`, service);
  },
  removeService(id) {
    return apiClient.delete(`/services/${id}`);
  },

  // Company Services
  getCompanyService(id) {
    return apiClient.get(`/company_services/${id}`);
  },
  createCompanyService(service) {
    return apiClient.post(`/api/company_services`, service);
  },
  updateCompanyService(id, service) {
    return apiClient.patch(`/api/company_services/${id}`, service);
  },
  removeCompanyService(id) {
    return apiClient.delete(`/api/company_services/${id}`);
  },

  // Users
  getUsers({ page = 1 }) {
    return apiClient.get(`/api/users?page=${page}`);
  },
  getUser(id) {
    return apiClient.get(`/users/${id}`);
  },
  adminCreateUser(user) {
    return apiClient.post(`/api/users`, user);
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
  getEmployees({ page = 1 }) {
    return apiClient.get(`/api/users/employees?page=${page}`);
  },

  // Bookings
  getBookings({ page = 1 }) {
    return apiClient.get(`/api/bookings?page=${page}`);
  },
  getCompanyBookings({ companyId, page = 1, pagination = true }) {
    return apiClient.get(
      `/api/companies/${companyId}/bookings?page=${page}&pagination=${pagination}`,
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
    return apiClient.post(`/api/bookings`, booking, config);
  },
  removeBooking(bookingId) {
    return apiClient.delete(`/api/bookings/${bookingId}`);
  },
  getEmployeeBookings(employeeId) {
    return apiClient.get(`/api/employee/${employeeId}/bookings`);
  },

  // Specialities
  getSpecialities({ pagination = false, page = 1 }) {
    return apiClient.get(`/specialities?pagination=${pagination}&page=${page}`);
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
    return apiClient.get("/api/requests?page=" + page);
  },
  createRequest(request) {
    return apiClient.post("/requests", request);
  },

  acceptRequest(request) {
    const id = request["@id"].split("/").pop();
    return apiClient.post(`/api/requests/${id}/validate`, { status: true });
  },
  declineRequest(request) {
    const id = request["@id"].split("/").pop();
    return apiClient.post(`/api/requests/${id}/validate`, { status: false });
  },

  // Schedule
  getCompanyEmployeesSchedule(companyId) {
    return apiClient.get(`/companies/${companyId}/employees/planning`);
  },
  getCompanySchedule(companyId) {
    return apiClient.get(`/companies/${companyId}/planning`);
  },
  getUserSchedule(userId) {
    return apiClient.get(`/api/users/${userId}/planning`);
  },

  // Statistics
  getSuperAdminStatistics() {
    return apiClient.get(`/api/stats/super_admin`);
  },
  getAdminStatistics(id) {
    return apiClient.get(`/api/stats/admin/${id}`);
  },

  // Unavailabilities
  createUnavailability(unavailability) {
    return apiClient.post(`/api/unavailabilities`, unavailability);
  },
};

export default apiService;
