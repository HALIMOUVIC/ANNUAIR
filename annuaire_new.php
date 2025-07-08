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
    <title>Annuaire d'Entreprise - Interface Moderne</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e40af',
                        'primary-dark': '#1e3a8a',
                        'secondary': '#3b82f6',
                        'accent': '#60a5fa',
                        'surface': '#f8fafc',
                        'card': '#ffffff',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.4s ease-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideDown: {
                            '0%': { opacity: '0', maxHeight: '0' },
                            '100%': { opacity: '1', maxHeight: '1000px' },
                        },
                        slideUp: {
                            '0%': { opacity: '1', maxHeight: '1000px' },
                            '100%': { opacity: '0', maxHeight: '0' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        },
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'inner-glow': 'inset 0 2px 4px 0 rgba(59, 130, 246, 0.1)',
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6, #1e40af);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #2563eb, #1e3a8a);
        }

        /* Smooth transitions for all elements */
        * {
            transition: all 0.3s ease;
        }

        /* Modern card styling */
        .modern-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            border-color: rgba(59, 130, 246, 0.2);
        }

        /* Enhanced button styling */
        .toggle-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            border: none;
            color: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .toggle-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
        }

        .toggle-button:active {
            transform: translateY(0);
        }

        /* Content area animations */
        .content-area {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(-10px);
        }

        .content-area.active {
            max-height: 2000px;
            opacity: 1;
            transform: translateY(0);
        }

        /* Icon animations */
        .chevron-icon {
            transition: transform 0.3s ease;
        }

        .chevron-icon.rotated {
            transform: rotate(90deg);
        }

        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        /* Search box styling */
        .search-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 50px;
            padding: 12px 24px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-box:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* Stats cards */
        .stats-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 197, 253, 0.05) 100%);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Enhanced responsive design */
        @media (max-width: 768px) {
            .modern-card {
                margin: 0.5rem;
            }
            
            .toggle-button {
                padding: 0.8rem 1.2rem;
                font-size: 0.9rem;
            }
            
            .stats-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 font-sans">
    <!-- Navigation Sidebar -->
    <nav class="fixed left-0 top-0 h-full w-80 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white shadow-2xl z-50 transform transition-transform duration-300">
        <div class="p-8">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-12">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-sitemap text-2xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-center bg-gradient-to-r from-blue-400 to-blue-200 bg-clip-text text-transparent">
                    Annuaire PRO
                </h1>
                <p class="text-slate-400 text-sm mt-2 text-center">Interface Moderne</p>
            </div>

            <!-- Navigation Menu -->
            <div class="space-y-4">
                <a href="index.php" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-slate-700 transition-all duration-300 group">
                    <i class="fas fa-plus text-blue-400 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Ajouter Division</span>
                </a>
                <a href="annuaire.php" class="flex items-center space-x-4 p-4 rounded-xl bg-slate-700 text-blue-400 shadow-inner">
                    <i class="fas fa-sitemap"></i>
                    <span class="font-medium">Annuaire</span>
                </a>
            </div>

            <!-- Stats Section -->
            <div class="mt-12 p-4 bg-slate-800 rounded-xl">
                <h3 class="text-lg font-semibold mb-4 text-blue-300">Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Divisions</span>
                        <span class="text-white font-bold"><?= count($filtered) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Services</span>
                        <span class="text-white font-bold">
                            <?= array_sum(array_map(function($div) { return count($div['departements'] ?? []); }, $filtered)) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-slate-700">
            <p class="text-center text-slate-400 text-sm">© 2025 Annuaire PRO</p>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="ml-80 min-h-screen p-8">
        <!-- Header Section -->
        <header class="mb-8 text-center">
            <div class="inline-block p-6 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full mb-6 shadow-xl">
                <i class="fas fa-building text-4xl text-white"></i>
            </div>
            <h1 class="text-6xl font-bold bg-gradient-to-r from-blue-800 via-blue-600 to-blue-500 bg-clip-text text-transparent mb-4">
                Annuaire d'Entreprise
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Explorez l'organisation complète avec notre interface moderne et intuitive
            </p>
        </header>

        <!-- Search and Filter Section -->
        <section class="mb-12">
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <i class="fas fa-search absolute left-6 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Rechercher une division, service ou section..." 
                        class="search-box w-full pl-14 pr-6"
                    >
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <section class="max-w-7xl mx-auto">
            <div id="directoryGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($filtered as $i => $div): ?>
                    <div class="modern-card rounded-2xl p-8 division-card" data-division="<?= strtolower($div['division']) ?>">
                        <!-- Division Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-building text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-800"><?= htmlspecialchars($div['division']) ?></h2>
                                    <p class="text-slate-500">Division organisationnelle</p>
                                </div>
                            </div>
                            <button 
                                class="toggle-button division-toggle" 
                                data-target="division-<?= $i ?>"
                                aria-expanded="false"
                            >
                                <i class="fas fa-chevron-right chevron-icon mr-2"></i>
                                Détails
                            </button>
                        </div>

                        <!-- Division Content -->
                        <div id="division-<?= $i ?>" class="content-area">
                            <div class="space-y-6">
                                <!-- Division Info -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                                        Informations Direction
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-user-tie text-blue-500"></i>
                                            <div>
                                                <span class="text-sm text-slate-500">Chef de division</span>
                                                <p class="font-semibold text-slate-800"><?= htmlspecialchars($div['chef']) ?></p>
                                            </div>
                                        </div>
                                        <?php if($div['phone']): ?>
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-phone text-green-500"></i>
                                                <div>
                                                    <span class="text-sm text-slate-500">Téléphone</span>
                                                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($div['phone']) ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($div['secretariat']): ?>
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-user-secret text-purple-500"></i>
                                                <div>
                                                    <span class="text-sm text-slate-500">Secrétariat</span>
                                                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($div['secretariat']) ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($div['ord']): ?>
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-clipboard-list text-orange-500"></i>
                                                <div>
                                                    <span class="text-sm text-slate-500">Ordonnancement</span>
                                                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($div['ord']) ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Services/Departments -->
                                <?php if (!empty($div['departements'])): ?>
                                    <div class="space-y-4">
                                        <h3 class="text-xl font-semibold text-slate-800 flex items-center">
                                            <i class="fas fa-cubes text-blue-500 mr-3"></i>
                                            Services (<?= count($div['departements']) ?>)
                                        </h3>
                                        
                                        <div class="space-y-4">
                                            <?php foreach ($div['departements'] as $j => $dep): ?>
                                                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-briefcase text-white"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($dep['name']) ?></h4>
                                                                <?php if($dep['phone']): ?>
                                                                    <p class="text-sm text-slate-500 flex items-center">
                                                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                                                        <?= htmlspecialchars($dep['phone']) ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($dep['sections'])): ?>
                                                            <button 
                                                                class="text-blue-600 hover:text-blue-700 font-medium service-toggle"
                                                                data-target="service-<?= $i ?>-<?= $j ?>"
                                                            >
                                                                <i class="fas fa-chevron-right chevron-icon mr-1"></i>
                                                                Sections (<?= count($dep['sections']) ?>)
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Sections -->
                                                    <?php if (!empty($dep['sections'])): ?>
                                                        <div id="service-<?= $i ?>-<?= $j ?>" class="content-area">
                                                            <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-lg p-4 border border-slate-100">
                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                    <?php foreach ($dep['sections'] as $sec): ?>
                                                                        <div class="bg-white rounded-lg p-4 border border-slate-200 hover:border-blue-300 transition-all duration-300">
                                                                            <div class="flex items-center space-x-3 mb-2">
                                                                                <i class="fas fa-layer-group text-blue-500"></i>
                                                                                <h5 class="font-semibold text-slate-800"><?= htmlspecialchars($sec['name']) ?></h5>
                                                                            </div>
                                                                            <div class="space-y-1">
                                                                                <?php if($sec['phone']): ?>
                                                                                    <p class="text-sm text-slate-600 flex items-center">
                                                                                        <i class="fas fa-phone text-green-500 mr-2 text-xs"></i>
                                                                                        <?= htmlspecialchars($sec['phone']) ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                                <?php if(!empty($sec['phone2'])): ?>
                                                                                    <p class="text-sm text-slate-600 flex items-center">
                                                                                        <i class="fas fa-phone text-blue-500 mr-2 text-xs"></i>
                                                                                        <?= htmlspecialchars($sec['phone2']) ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16">
            <div class="inline-block p-8 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full mb-6">
                <i class="fas fa-search text-4xl text-slate-400"></i>
            </div>
            <h3 class="text-2xl font-semibold text-slate-800 mb-4">Aucun résultat trouvé</h3>
            <p class="text-slate-600 max-w-md mx-auto">
                Essayez de modifier votre recherche ou explorez toutes les divisions disponibles.
            </p>
        </div>
    </main>

    <!-- Enhanced JavaScript -->
    <script>
        // Modern Directory Management System
        class DirectoryManager {
            constructor() {
                this.searchInput = document.getElementById('searchInput');
                this.directoryGrid = document.getElementById('directoryGrid');
                this.emptyState = document.getElementById('emptyState');
                this.divisionCards = document.querySelectorAll('.division-card');
                
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.setupSearch();
                this.addLoadingAnimations();
            }

            setupEventListeners() {
                // Division toggle buttons
                document.querySelectorAll('.division-toggle').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleContent(button);
                    });
                });

                // Service toggle buttons
                document.querySelectorAll('.service-toggle').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleContent(button);
                    });
                });
            }

            toggleContent(button) {
                const targetId = button.dataset.target;
                const content = document.getElementById(targetId);
                const icon = button.querySelector('.chevron-icon');
                
                if (!content) return;

                // Add loading state
                const isExpanded = content.classList.contains('active');
                
                if (isExpanded) {
                    // Collapse
                    content.classList.remove('active');
                    icon.classList.remove('rotated');
                    button.setAttribute('aria-expanded', 'false');
                    
                    // Update button text if it's a division button
                    if (button.classList.contains('division-toggle')) {
                        button.innerHTML = '<i class="fas fa-chevron-right chevron-icon mr-2"></i>Détails';
                    }
                } else {
                    // Expand
                    content.classList.add('active');
                    icon.classList.add('rotated');
                    button.setAttribute('aria-expanded', 'true');
                    
                    // Update button text if it's a division button
                    if (button.classList.contains('division-toggle')) {
                        button.innerHTML = '<i class="fas fa-chevron-down chevron-icon mr-2"></i>Masquer';
                    }
                    
                    // Scroll to content with smooth animation
                    setTimeout(() => {
                        content.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 200);
                }
            }

            setupSearch() {
                if (!this.searchInput) return;

                let searchTimeout;
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.performSearch(e.target.value.toLowerCase().trim());
                    }, 300);
                });

                // Clear search on escape
                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.searchInput.value = '';
                        this.performSearch('');
                    }
                });
            }

            performSearch(query) {
                let hasResults = false;

                this.divisionCards.forEach(card => {
                    const divisionName = card.dataset.division;
                    const cardContent = card.textContent.toLowerCase();
                    const shouldShow = query === '' || cardContent.includes(query);

                    if (shouldShow) {
                        card.style.display = 'block';
                        card.classList.add('animate-fade-in');
                        hasResults = true;
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('animate-fade-in');
                    }
                });

                // Show/hide empty state
                if (hasResults) {
                    this.emptyState.classList.add('hidden');
                    this.directoryGrid.classList.remove('hidden');
                } else {
                    this.emptyState.classList.remove('hidden');
                    this.directoryGrid.classList.add('hidden');
                }
            }

            addLoadingAnimations() {
                // Add staggered animation to cards
                this.divisionCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.1}s`;
                    card.classList.add('animate-fade-in');
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new DirectoryManager();
            
            // Add smooth scrolling for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && e.ctrlKey) {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        });
    </script>
</body>
</html>