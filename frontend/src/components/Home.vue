<template>
    <div class="home">
      <h1>Bienvenido a VueBlog</h1>
      <p>Por favor registrate o iniciá sesion para poder acceder al blog</p>
      <div class="cta-buttons">
        <router-link to="/login" class="btn btn-primary">Iniciar Sesión</router-link>
        <router-link to="/register" class="btn btn-secondary">Registrarme</router-link>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Home'
  }
  </script>
  
  <style scoped>
  .home {
    text-align: center;
    padding: 2rem 0;
  }
  
  h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
  }
  
  p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
  }
  
  .cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
  }
  
  .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    font-size: 1rem;
    text-decoration: none;
    transition: background-color 0.2s;
  }
  
  .btn-primary {
    background-color: var(--color-primary);
    color: white;
  }
  
  .btn-primary:hover {
    background-color: #5e77d4;
  }
  
  .btn-secondary {
    background-color: var(--color-secondary);
    color: white;
  }
  
  .btn-secondary:hover {
    background-color: #3ca374;
  }
  </style>