<template>
    <div class="user-profile">
      <h2>Perfil del usuario</h2>
      <div class="profile-info">
        <p><strong>Nombre:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <!-- Aquí puedes agregar más información del perfil si lo deseas -->
      </div>
    </div>
  </template>
  
  <script>
  import { useAuth } from '../utils/auth'
  
  export default {
    setup() {
      const { user } = useAuth()
      return { user }
    }
  }
  </script>
  
  <style scoped>
  .user-profile {
    max-width: 600px;
    margin: 0 auto;
    padding: 2rem;
    background-color: var(--color-background-light);
    border-radius: 8px;
  }
  
  .profile-info {
    margin-top: 1rem;
  }
  
  .profile-info p {
    margin-bottom: 0.5rem;
  }
  </style>