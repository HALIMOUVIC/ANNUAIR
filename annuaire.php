<?php
// Fichier de stockage des divisions
$contacts_file = 'contacts.txt';
$divisions = file_exists($contacts_file) ? file($contacts_file, FILE_IGNORE_NEW_LINES) : [];
$filtered = [];
foreach ($divisions as $line) {
    $data = json_decode($line, true);
    if ($data) $filtered[] = $data;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annuaire Moderne - Interface Professionnelle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    colors: {
                        'primary': {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        'secondary': {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); max-height: 0; }
            to { opacity: 1; transform: translateY(0); max-height: 1000px; }
        }
        
        @keyframes slideUp {
            from { opacity: 1; transform: translateY(0); max-height: 1000px; }
            to { opacity: 0; transform: translateY(-20px); max-height: 0; }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Modern Card Styles */
        .modern-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        
        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
            border-color: #0ea5e9;
        }

        .division-card {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            transition: all 0.3s ease;
        }
        
        .division-card:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(14, 165, 233, 0.4);
        }

        .department-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-left: 4px solid #0ea5e9;
            transition: all 0.3s ease;
        }
        
        .department-card:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border-left-color: #0284c7;
            transform: translateX(4px);
        }

        .section-item {
            background: linear-gradient(90deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .section-item:hover {
            background: linear-gradient(90deg, #f0f9ff 0%, #e0f2fe 100%);
            border-color: #0ea5e9;
        }

        /* Content Animation */
        .content-expanded {
            max-height: 2000px;
            overflow: visible;
            opacity: 1;
            transition: max-height 0.4s ease-out, opacity 0.4s ease-out;
        }
        
        .content-collapsed {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        }

        /* Search Box */
        .search-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        /* Responsive Grid */
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .responsive-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Modern Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-2 rounded-xl">
                        <i class="fas fa-sitemap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Annuaire Pro</h1>
                        <p class="text-sm text-gray-600">Interface Moderne</p>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-md mx-8">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Rechercher une division, service ou personne..."
                            class="search-box w-full pl-10 pr-4 py-2 rounded-xl focus:outline-none"
                        >
                    </div>
                </div>
                
                <!-- Filter Buttons -->
                <div class="flex items-center space-x-2">
                    <button id="expandAllBtn" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                        <i class="fas fa-expand-arrows-alt mr-2"></i>Tout déplier
                    </button>
                    <button id="collapseAllBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-compress-arrows-alt mr-2"></i>Tout replier
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="modern-card rounded-xl p-6 text-center">
                <div class="bg-primary-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900" id="divisionsCount"><?= count($filtered) ?></h3>
                <p class="text-gray-600">Divisions</p>
            </div>
            
            <div class="modern-card rounded-xl p-6 text-center">
                <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-briefcase text-green-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900" id="departmentsCount">
                    <?php 
                    $deptCount = 0;
                    foreach ($filtered as $div) {
                        $deptCount += count($div['departements'] ?? []);
                    }
                    echo $deptCount;
                    ?>
                </h3>
                <p class="text-gray-600">Services</p>
            </div>
            
            <div class="modern-card rounded-xl p-6 text-center">
                <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900" id="sectionsCount">
                    <?php 
                    $sectCount = 0;
                    foreach ($filtered as $div) {
                        foreach ($div['departements'] ?? [] as $dept) {
                            $sectCount += count($dept['sections'] ?? []);
                        }
                    }
                    echo $sectCount;
                    ?>
                </h3>
                <p class="text-gray-600">Sections</p>
            </div>
        </div>

        <!-- Directory Grid -->
        <div class="responsive-grid" id="directoryGrid">
            <?php foreach ($filtered as $i => $div): ?>
                <div class="division-container animate-fade-in" data-search-content="<?= htmlspecialchars(strtolower($div['division'] . ' ' . $div['chef'] . ' ' . ($div['secretariat'] ?? '') . ' ' . ($div['ord'] ?? ''))) ?>">
                    <!-- Division Card -->
                    <div class="division-card rounded-xl p-6 cursor-pointer" data-toggle="division-<?= $i ?>">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-white/20 rounded-lg p-2">
                                    <i class="fas fa-building text-2xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold"><?= htmlspecialchars($div['division']) ?></h2>
                                    <p class="text-blue-100">Division</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down transform transition-transform duration-300 division-arrow" data-target="division-<?= $i ?>"></i>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="bg-white/10 rounded-lg p-3">
                                <i class="fas fa-user-tie mb-2 text-blue-200"></i>
                                <p class="text-blue-100">Chef de division</p>
                                <p class="font-semibold"><?= htmlspecialchars($div['chef']) ?></p>
                            </div>
                            <?php if($div['phone']): ?>
                            <div class="bg-white/10 rounded-lg p-3">
                                <i class="fas fa-phone mb-2 text-blue-200"></i>
                                <p class="text-blue-100">Téléphone</p>
                                <p class="font-semibold"><?= htmlspecialchars($div['phone']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Division Content (Initially Collapsed) -->
                    <div id="division-<?= $i ?>" class="content-collapsed mt-4 space-y-4">
                        <!-- Division Info -->
                        <div class="modern-card rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-primary-500 mr-2"></i>
                                Informations détaillées
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-user-tie text-primary-500"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Chef de division</p>
                                        <p class="font-semibold"><?= htmlspecialchars($div['chef']) ?></p>
                                    </div>
                                </div>
                                
                                <?php if($div['secretariat']): ?>
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-user-secret text-primary-500"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Secrétariat</p>
                                        <p class="font-semibold"><?= htmlspecialchars($div['secretariat']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($div['ord']): ?>
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-clipboard-list text-primary-500"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Ordonnancement</p>
                                        <p class="font-semibold"><?= htmlspecialchars($div['ord']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($div['phone']): ?>
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-phone text-primary-500"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Téléphone</p>
                                        <p class="font-semibold"><?= htmlspecialchars($div['phone']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Departments -->
                        <?php if (!empty($div['departements'])): ?>
                            <div class="modern-card rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-cubes text-primary-500 mr-2"></i>
                                    Services (<?= count($div['departements']) ?>)
                                </h3>
                                
                                <div class="space-y-3">
                                    <?php foreach ($div['departements'] as $j => $dep): ?>
                                        <div class="department-card rounded-lg p-4 cursor-pointer" data-toggle="dept-<?= $i ?>-<?= $j ?>">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="bg-primary-100 rounded-lg p-2">
                                                        <i class="fas fa-briefcase text-primary-600"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($dep['name']) ?></h4>
                                                        <?php if($dep['phone']): ?>
                                                            <p class="text-sm text-gray-600">
                                                                <i class="fas fa-phone text-primary-500 mr-1"></i>
                                                                <?= htmlspecialchars($dep['phone']) ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-down transform transition-transform duration-300 dept-arrow" data-target="dept-<?= $i ?>-<?= $j ?>"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Department Content (Initially Collapsed) -->
                                        <div id="dept-<?= $i ?>-<?= $j ?>" class="content-collapsed ml-8">
                                            <?php if (!empty($dep['sections'])): ?>
                                                <div class="space-y-2">
                                                    <h5 class="font-medium text-gray-700 flex items-center">
                                                        <i class="fas fa-sitemap text-gray-500 mr-2"></i>
                                                        Sections (<?= count($dep['sections']) ?>)
                                                    </h5>
                                                    <?php foreach ($dep['sections'] as $sec): ?>
                                                        <div class="section-item rounded-lg p-3 ml-4">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center space-x-3">
                                                                    <i class="fas fa-angle-right text-gray-400"></i>
                                                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($sec['name']) ?></span>
                                                                </div>
                                                                <div class="flex items-center space-x-4">
                                                                    <?php if($sec['phone']): ?>
                                                                        <div class="flex items-center space-x-1 text-sm text-gray-600">
                                                                            <i class="fas fa-phone text-primary-500"></i>
                                                                            <span><?= htmlspecialchars($sec['phone']) ?></span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if(!empty($sec['phone2'])): ?>
                                                                        <div class="flex items-center space-x-1 text-sm text-gray-600">
                                                                            <i class="fas fa-phone text-green-500"></i>
                                                                            <span><?= htmlspecialchars($sec['phone2']) ?></span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-16 hidden">
            <div class="max-w-md mx-auto">
                <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-600">Essayez de modifier votre recherche ou d'utiliser d'autres mots-clés.</p>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        // Enhanced Toggle Functionality
        function toggleContent(targetId, arrow) {
            const content = document.getElementById(targetId);
            const arrowElement = document.querySelector(`[data-target="${targetId}"]`);
            
            if (content.classList.contains('content-collapsed')) {
                // Expand
                content.classList.remove('content-collapsed');
                content.classList.add('content-expanded');
                if (arrowElement) {
                    arrowElement.classList.add('rotate-180');
                }
            } else {
                // Collapse
                content.classList.remove('content-expanded');
                content.classList.add('content-collapsed');
                if (arrowElement) {
                    arrowElement.classList.remove('rotate-180');
                }
            }
        }

        // Division Toggle
        document.querySelectorAll('[data-toggle^="division-"]').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-toggle');
                const arrow = this.querySelector('.division-arrow');
                toggleContent(targetId, arrow);
            });
        });

        // Department Toggle
        document.querySelectorAll('[data-toggle^="dept-"]').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const targetId = this.getAttribute('data-toggle');
                const arrow = this.querySelector('.dept-arrow');
                toggleContent(targetId, arrow);
            });
        });

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const directoryGrid = document.getElementById('directoryGrid');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const divisions = document.querySelectorAll('.division-container');
            let visibleCount = 0;

            divisions.forEach(division => {
                const content = division.getAttribute('data-search-content');
                if (content.includes(searchTerm)) {
                    division.style.display = 'block';
                    division.classList.add('animate-fade-in');
                    visibleCount++;
                } else {
                    division.style.display = 'none';
                    division.classList.remove('animate-fade-in');
                }
            });

            if (visibleCount === 0 && searchTerm !== '') {
                noResults.classList.remove('hidden');
                directoryGrid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                directoryGrid.classList.remove('hidden');
            }
        });

        // Expand All / Collapse All
        document.getElementById('expandAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.content-collapsed').forEach(content => {
                content.classList.remove('content-collapsed');
                content.classList.add('content-expanded');
            });
            document.querySelectorAll('.division-arrow, .dept-arrow').forEach(arrow => {
                arrow.classList.add('rotate-180');
            });
        });

        document.getElementById('collapseAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.content-expanded').forEach(content => {
                content.classList.remove('content-expanded');
                content.classList.add('content-collapsed');
            });
            document.querySelectorAll('.division-arrow, .dept-arrow').forEach(arrow => {
                arrow.classList.remove('rotate-180');
            });
        });

        // Smooth scroll to section when expanded
        document.querySelectorAll('[data-toggle]').forEach(element => {
            element.addEventListener('click', function() {
                setTimeout(() => {
                    this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            });
        });
    </script>
</body>
</html>
