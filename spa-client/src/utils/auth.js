/**
 * Authentication utility functions
 */

/**
 * Check if user is authenticated
 * @returns {Boolean} True if user is authenticated
 */
export const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  const user = localStorage.getItem('user');
  
  return !!token && !!user;
};

/**
 * Get the authentication token
 * @returns {String|null} The authentication token or null if not authenticated
 */
export const getToken = () => {
  return localStorage.getItem('token');
};

/**
 * Get the current authenticated user
 * @returns {Object|null} The user object or null if not authenticated
 */
export const getUser = () => {
  const user = localStorage.getItem('user');
  return user ? JSON.parse(user) : null;
};

/**
 * Check if the current user is an admin
 * @returns {Boolean} True if user is an admin
 */
export const isAdmin = () => {
  const user = getUser();
  return user && user.role === 'admin';
}; 