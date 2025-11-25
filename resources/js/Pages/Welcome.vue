<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Hero Section -->
      <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
          <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
              fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
              <polygon points="50,0 100,0 50,100 0,100" />
            </svg>

            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
              <div class="sm:text-center lg:text-left">
                <div class="flex justify-center lg:justify-start mb-6">
                  <img :src="'/LogoCaratula.png'" alt="Logo Fábrica"
                    class="h-32 w-auto drop-shadow-xl animate-fade-in-down" />
                </div>
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                  <span class="block xl:inline">Gestión Integral para</span>
                  <span class="block text-indigo-600">Fábrica Biodegradable</span>
                </h1>
                <p
                  class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                  Optimiza la producción, monitorea recursos en tiempo real y asegura la calidad de tus productos
                  sostenibles con nuestra plataforma de última generación.
                </p>
                <div v-if="!$page.props.auth.user" class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                  <div class="rounded-md shadow">
                    <a href="/login"
                      class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg transition-all duration-300 transform hover:scale-105">
                      Iniciar Sesión
                    </a>
                  </div>
                </div>
              </div>
            </main>
          </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-indigo-50">
          <div
            class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20">
            </div>
            <!-- Abstract Shapes -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-purple-400 opacity-20 blur-3xl">
            </div>
            <div
              class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-indigo-400 opacity-20 blur-3xl">
            </div>

            <div class="relative z-10 text-white text-center p-8">
              <svg class="w-32 h-32 mx-auto mb-4 text-indigo-200 opacity-80" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
              </svg>
              <h3 class="text-2xl font-bold mb-2">Tecnología Verde</h3>
              <p class="text-indigo-100">Innovación para un mundo más limpio</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="lg:text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Características</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
              Control total de tu planta
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
              Herramientas diseñadas para maximizar la eficiencia y minimizar el desperdicio.
            </p>
          </div>

          <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
              <div class="relative">
                <dt>
                  <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                  </div>
                  <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Monitoreo en Tiempo Real</p>
                </dt>
                <dd class="mt-2 ml-16 text-base text-gray-500">
                  Visualiza el estado de cada máquina y línea de producción al instante con actualizaciones via
                  WebSocket.
                </dd>
              </div>

              <div class="relative">
                <dt>
                  <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Eficiencia Energética</p>
                </dt>
                <dd class="mt-2 ml-16 text-base text-gray-500">
                  Analiza el consumo de recursos para optimizar costos y reducir la huella de carbono.
                </dd>
              </div>

              <div class="relative">
                <dt>
                  <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Control de Calidad</p>
                </dt>
                <dd class="mt-2 ml-16 text-base text-gray-500">
                  Seguimiento detallado de lotes y parámetros de calidad para asegurar el mejor producto final.
                </dd>
              </div>

              <div class="relative">
                <dt>
                  <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                  </div>
                  <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Configuración Flexible</p>
                </dt>
                <dd class="mt-2 ml-16 text-base text-gray-500">
                  Adapta el sistema a las necesidades específicas de tu planta con paneles personalizables.
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '../Layouts/MainLayout.vue';
</script>

<style scoped>
.animate-fade-in-down {
  animation: fadeInDown 0.8s ease-out;
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
