<template>
  <header class="app-header">
    <div class="nav-container">
      <!-- Logo -->
      <div class="logo-container">
        <router-link to="/">
          <img src="@/assets/logo.png" alt="Logotipo da Unicampo" height="32" />
          <span class="logo-text">Unicampo</span>
        </router-link>
      </div>
      
      <!-- Links de Navegação -->
      <div class="nav-items">
        <div class="nav-item">
          <router-link to="/" class="nav-link" exact>
            <span class="nav-text">Início</span>
            <div class="nav-indicator"></div>
          </router-link>
        </div>
        <div class="nav-item">
          <router-link to="/clientes" class="nav-link">
            <span class="nav-text">Clientes</span>
            <div class="nav-indicator"></div>
          </router-link>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
.app-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: center;
  padding-top: 24px;
  z-index: 1050;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 12px 24px;
  max-width: 960px;
  width: 90%;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 0, 0, 0.04);
}

.logo-container {
  display: flex;
  align-items: center;
}

.logo-container a {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.logo-text {
  font-weight: 600;
  font-size: 18px;
  color: #222;
  margin-left: 12px;
  letter-spacing: -0.2px;
}

.nav-items {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nav-item {
  position: relative;
}

.nav-link {
  display: block;
  color: #0a3b25;
  text-decoration: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 500;
  position: relative;
  transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.nav-text {
  position: relative;
  z-index: 2;
}

.nav-indicator {
  position: absolute;
  height: 3px;
  width: 0;
  background-color: #0a3b25;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 3px;
  transition: width 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  opacity: 0;
}

.nav-link:hover {
  color: #3e3e3e;
  background-color: rgba(133, 133, 133, 0.03);
}

.nav-link:hover .nav-indicator {
  width: 20px;
  opacity: 0.5;
}

.nav-link.router-link-exact-active,
.nav-link.router-link-active {
  color: #ffffff;
  background-color:#0a3b25;
}


/* Responsividade básica */
@media (max-width: 768px) {
  .nav-container {
    flex-direction: column;
    border-radius: 14px;
    padding: 16px;
  }
  
  .nav-items {
    margin-top: 16px;
    width: 100%;
    justify-content: center;
  }
  
  .logo-container {
    margin-bottom: 8px;
  }
}
</style>