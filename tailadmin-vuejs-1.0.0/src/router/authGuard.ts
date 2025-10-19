// src/router/guards/authGuard.ts
import type { Router } from 'vue-router'

export function setupAuthGuard(router: Router) {
  router.beforeEach((to, from, next) => {
    // List of routes that require authentication
    const protectedRoutes = [
      'Ecommerce',
      'Calendar',
      'Profile',
      'Form Elements',
      'Basic Tables',
      'Line Chart',
      'Bar Chart',
      'Alerts',
      'Avatars',
      'Badge',
      'Buttons',
      'Images',
      'Videos',
      'Blank',
    ]

    // Check if the route requires authentication
    const requiresAuth = protectedRoutes.includes(to.name as string)

    // Get token from localStorage
    const token = localStorage.getItem('auth_token')

    if (requiresAuth) {
      if (token) {
        // User has token, allow access
        next()
      } else {
        // No token, redirect to signin
        next('/signin')
      }
    } else {
      // Route doesn't require auth, allow access
      next()
    }
  })
}