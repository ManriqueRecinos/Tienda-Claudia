<div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center px-6 py-12">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full text-center">
        <div class="mb-6">
            <h1 class="text-9xl font-bold text-indigo-600">404</h1>
            <h2 class="text-2xl font-bold text-gray-900 mt-4">Página no encontrada</h2>
            <p class="text-gray-600 mt-2">Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
        </div>
        
        <div class="border-t border-gray-200 pt-6">
            <p class="text-gray-600 mb-4">Puedes intentar:</p>
            <div class="space-y-3">
                <a href="<?php echo APP_URL; ?>" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                    Volver al inicio
                </a>
                <a href="javascript:history.back()" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-300">
                    Regresar a la página anterior
                </a>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-center text-gray-500 text-sm">
        <p>© <?php echo date('Y'); ?> Tienda Claudia. Todos los derechos reservados.</p>
    </div>
</div>
