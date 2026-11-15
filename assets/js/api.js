/**
 * AHAMED DRAFTED — Reusable Fetch API Service Layer
 * Communicates with /api/v1/... endpoints with structured error handling.
 */
export const AhamedApiService = {
  baseUrl: '/api/v1',

  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}${endpoint}`;
    const headers = {
      'Accept': 'application/json',
      ...(options.body && !(options.body instanceof FormData) ? { 'Content-Type': 'application/json' } : {}),
      ...(options.headers || {})
    };
    const response = await fetch(url, { ...options, headers });
    const payload = await response.json().catch(() => ({ status: 'error', message: 'Invalid JSON response' }));
    if (!response.ok || payload.status === 'error') {
      throw new Error(payload.message || `API Error (${response.status})`);
    }
    return payload;
  },

  getProfile() { return this.request('/profile'); },
  getPosts(params = '') { return this.request(`/posts${params ? '?' + params : ''}`); },
  getPostBySlug(slug) { return this.request(`/posts/${encodeURIComponent(slug)}`); },
  getGallery() { return this.request('/gallery'); },
  getAlbumBySlug(slug) { return this.request(`/albums/${encodeURIComponent(slug)}`); },
  getMemories() { return this.request('/memories'); },
  getFriends() { return this.request('/friends'); },
  getProjects() { return this.request('/projects'); },
  submitGuestbook(data) {
    return this.request('/guestbook', { method: 'POST', body: JSON.stringify(data) });
  },

  // Admin Operations
  saveAdminRecord(module, data, id = null) {
    const method = id ? 'PUT' : 'POST';
    const path = id ? `/admin/${module}/${id}` : `/admin/${module}`;
    return this.request(path, { method, body: JSON.stringify(data) });
  },
  deleteAdminRecord(module, id) {
    return this.request(`/admin/${module}/${id}`, { method: 'DELETE' });
  },
  uploadMedia(formData) {
    return this.request('/admin/media/upload', { method: 'POST', body: formData });
  }
};
