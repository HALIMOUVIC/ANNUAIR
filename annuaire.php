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
    <title>Annuaire Téléphonique - Arborescence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Import Google Fonts for modern typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Custom scrollbar for a more modern look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #60a5fa, #3b82f6);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
        }

        /* Glassmorphism effects */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-card {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .glass-sidebar {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: linear-gradient(145deg, rgba(30, 64, 175, 0.9), rgba(37, 99, 235, 0.9));
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Enhanced treeview content styling */
        .tree-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
        }
        .tree-content.open {
            max-height: 1000px;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Modern visual lines for hierarchy */
        .tree-branch-line {
            border-left: 2px solid rgba(59, 130, 246, 0.3);
            margin-left: 20px;
            padding-left: 20px;
            position: relative;
        }

        .tree-branch-line::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 20px;
            width: 12px;
            height: 2px;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.3), transparent);
        }

        /* Enhanced button styles with micro-interactions */
        .tree-toggle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .tree-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .tree-toggle:hover::before {
            left: 100%;
        }

        .tree-toggle:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        /* Enhanced card hover effects */
        .division-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .division-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(147, 197, 253, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .division-card:hover::before {
            opacity: 1;
        }

        .division-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.15);
        }

        /* Search functionality styles */
        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-input:focus {
            border-color: rgba(59, 130, 246, 0.6);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Enhanced loading states */
        .loading-shimmer {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        /* Enhanced rotation animation for chevrons */
        .rotate-90 {
            transform: rotate(90deg);
        }

        /* Control buttons styling */
        .control-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .control-btn {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .control-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        /* Enhanced contact information display */
        .contact-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(147, 197, 253, 0.05));
            border-radius: 0.75rem;
            padding: 1rem;
            margin: 0.5rem 0;
            border: 1px solid rgba(59, 130, 246, 0.1);
            transition: all 0.3s ease;
        }

        .contact-info:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 768px) {
            .glass-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .glass-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 50;
                background: rgba(59, 130, 246, 0.9);
                color: white;
                padding: 0.5rem;
                border-radius: 0.5rem;
                border: none;
                cursor: pointer;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        /* Hidden class for search functionality */
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-200 min-h-screen flex font-sans antialiased">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
        <i class="fa fa-bars text-xl"></i>
    </button>

    <aside class="w-64 glass-sidebar text-white flex flex-col py-8 px-4 shadow-2xl min-h-screen fixed left-0 top-0 bottom-0 z-20">
        <div class="flex flex-col items-center mb-10">
            <div class="bg-white/20 rounded-full p-4 mb-3 shadow-lg backdrop-blur-sm">
                <i class="fa fa-sitemap text-white text-3xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-center tracking-wide">Annuaire PRO</h2>
            <div class="w-16 h-0.5 bg-white/30 mt-2"></div>
        </div>
        <nav class="flex flex-col gap-3">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-white/20 transition-all duration-300 ease-in-out group backdrop-blur-sm">
                <i class="fa fa-plus text-lg group-hover:scale-110 transition-transform"></i>
                <span class="text-lg font-medium">Ajouter une division</span>
            </a>
            <a href="annuaire.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/30 text-white shadow-md backdrop-blur-sm">
                <i class="fa fa-sitemap text-lg"></i>
                <span class="text-lg font-medium">Annuaire</span>
            </a>
        </nav>
        <div class="mt-auto text-xs text-center text-blue-200 pt-8 border-t border-white/20 mx-4 pt-4">
            <div class="flex items-center justify-center gap-2">
                <i class="fa fa-copyright"></i>
                <span>Annuaire 2025</span>
            </div>
        </div>
    </aside>
    
    <main class="flex-1 flex flex-col items-center py-8 ml-64 w-full min-w-0 main-content">
        <div class="w-full max-w-6xl glass-card rounded-3xl shadow-2xl p-10 border border-white/20 transform hover:shadow-3xl transition-all duration-300 ease-in-out">
            <div class="flex flex-col items-center mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full p-6 mb-4 shadow-lg">
                    <i class="fa fa-sitemap text-white text-5xl"></i>
                </div>
                <h1 class="text-5xl font-extrabold text-center text-gray-800 tracking-tight leading-tight mb-2">
                    Annuaire d'Entreprise
                </h1>
                <p class="text-gray-600 text-center mt-2 text-xl font-light">
                    Explorez l'organisation et les contacts de notre annuaire interactif
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mt-4"></div>
            </div>

            <!-- Search and Control Section -->
            <div class="mb-8">
                <div class="search-container">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Rechercher une division, service ou section..."
                            class="search-input w-full px-6 py-4 pl-14 rounded-2xl text-gray-700 font-medium text-lg"
                            onkeyup="searchAnnuaire()"
                        >
                        <i class="fa fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                    </div>
                </div>
                
                <div class="control-buttons">
                    <button class="control-btn" onclick="expandAll()">
                        <i class="fa fa-expand mr-2"></i>
                        Tout développer
                    </button>
                    <button class="control-btn" onclick="collapseAll()">
                        <i class="fa fa-compress mr-2"></i>
                        Tout réduire
                    </button>
                    <button class="control-btn" onclick="clearSearch()">
                        <i class="fa fa-times mr-2"></i>
                        Effacer la recherche
                    </button>
                </div>
            </div>
            <ul id="tree-root" class="space-y-6">
            <?php foreach ($filtered as $i => $div): ?>
                <li class="division-card glass-card rounded-2xl p-6 shadow-lg border border-white/30 search-item" data-search="<?= strtolower(htmlspecialchars($div['division'] . ' ' . $div['chef'] . ' ' . $div['secretariat'] . ' ' . $div['ord'])) ?>">
                    <button type="button" class="tree-toggle flex items-center justify-between w-full text-left font-bold text-gray-800 hover:text-blue-700 focus:outline-none rounded-xl p-4 transition-all duration-300" data-target="div-content-<?= $i ?>">
                        <span class="text-2xl flex items-center font-semibold">
                            <i class="fa fa-building text-blue-600 mr-4 text-3xl"></i>
                            <?= htmlspecialchars($div['division']) ?>
                        </span>
                        <i class="fa fa-chevron-right text-blue-500 transition-transform duration-300 text-xl"></i>
                    </button>
                    <div id="div-content-<?= $i ?>" class="tree-content">
                        <div class="py-4 tree-branch-line">
                            <ul class="space-y-4">
                                <li class="contact-info">
                                    <p class="text-gray-700 text-lg">
                                        <span class="font-semibold text-blue-700">
                                            <i class="fa fa-user-tie mr-3 text-blue-500"></i>Chef division :
                                        </span> 
                                        <span class="font-medium"><?= htmlspecialchars($div['chef']) ?></span>
                                        <?php if($div['phone']): ?>
                                            <span class="text-gray-600 text-base ml-4 bg-blue-50 px-3 py-1 rounded-full inline-flex items-center">
                                                <i class="fa fa-phone text-blue-500 mr-2"></i>
                                                <?= htmlspecialchars($div['phone']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </li>
                                <?php if($div['secretariat']): ?>
                                <li class="contact-info">
                                    <p class="text-gray-700 text-lg">
                                        <span class="font-semibold text-blue-700">
                                            <i class="fa fa-user-secret mr-3 text-blue-500"></i>Secrétariat :
                                        </span> 
                                        <span class="font-medium"><?= htmlspecialchars($div['secretariat']) ?></span>
                                    </p>
                                </li>
                                <?php endif; ?>
                                <?php if($div['ord']): ?>
                                <li class="contact-info">
                                    <p class="text-gray-700 text-lg">
                                        <span class="font-semibold text-blue-700">
                                            <i class="fa fa-clipboard-list mr-3 text-blue-500"></i>Ordonnancement :
                                        </span> 
                                        <span class="font-medium"><?= htmlspecialchars($div['ord']) ?></span>
                                    </p>
                                </li>
                                <?php endif; ?>

                                <?php if (!empty($div['departements'])): ?>
                                    <li class="mt-6">
                                        <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                            <i class="fa fa-cubes mr-4 text-blue-500 text-2xl"></i>
                                            Services
                                        </h3>
                                        <ul class="space-y-4 tree-branch-line">
                                        <?php foreach ($div['departements'] as $j => $dep): ?>
                                            <li class="division-card glass-card rounded-xl p-4 border border-white/20 search-item" data-search="<?= strtolower(htmlspecialchars($dep['name'])) ?>">
                                                <button type="button" class="tree-toggle flex items-center justify-between w-full text-left text-blue-800 hover:text-blue-900 focus:outline-none py-3 rounded-lg transition-all duration-300" data-target="dep-content-<?= $i ?>-<?= $j ?>">
                                                    <span class="text-xl font-semibold flex items-center">
                                                        <i class="fa fa-briefcase text-blue-500 mr-3 text-xl"></i>
                                                        <?= htmlspecialchars($dep['name']) ?>
                                                    </span>
                                                    <i class="fa fa-chevron-right text-gray-500 transition-transform duration-300 text-lg"></i>
                                                </button>
                                                <div id="dep-content-<?= $i ?>-<?= $j ?>" class="tree-content">
                                                    <div class="py-3 text-gray-800 tree-branch-line">
                                                        <ul class="space-y-3">
                                                            <?php if($dep['phone']): ?>
                                                            <li class="contact-info">
                                                                <p class="text-base">
                                                                    <span class="font-semibold text-blue-700">
                                                                        <i class="fa fa-phone text-blue-500 mr-2"></i>Téléphone :
                                                                    </span>
                                                                    <span class="bg-blue-50 px-3 py-1 rounded-full inline-flex items-center ml-2">
                                                                        <?= htmlspecialchars($dep['phone']) ?>
                                                                    </span>
                                                                </p>
                                                            </li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($dep['sections'])): ?>
                                                                <li class="mt-4">
                                                                    <p class="font-bold text-lg mb-3 flex items-center text-gray-800">
                                                                        <i class="fa fa-sitemap mr-3 text-blue-500"></i>
                                                                        Sections
                                                                    </p>
                                                                    <ul class="ml-6 space-y-2">
                                                                        <?php foreach ($dep['sections'] as $sec): ?>
                                                                            <li class="flex items-center text-base py-2 contact-info search-item" data-search="<?= strtolower(htmlspecialchars($sec['name'])) ?>">
                                                                                <i class="fa fa-angle-right text-blue-400 mr-3"></i>
                                                                                <span class="font-semibold text-gray-700"><?= htmlspecialchars($sec['name']) ?></span>
                                                                                <?php if($sec['phone']): ?>
                                                                                    <span class="text-gray-600 ml-4 bg-blue-50 px-2 py-1 rounded-full text-sm inline-flex items-center">
                                                                                        <i class="fa fa-phone text-blue-400 mr-1"></i>
                                                                                        <?= htmlspecialchars($sec['phone']) ?>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                                <?php if(!empty($sec['phone2'])): ?>
                                                                                    <span class="text-gray-500 ml-2 bg-gray-50 px-2 py-1 rounded-full text-sm inline-flex items-center">
                                                                                        <i class="fa fa-phone text-blue-300 mr-1"></i>
                                                                                        <?= htmlspecialchars($sec['phone2']) ?>
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <script>
        // Enhanced treeview functionality with improved animations
        document.querySelectorAll('.tree-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const content = document.getElementById(targetId);
                const icon = this.querySelector('i.fa-chevron-right');

                if (content) {
                    if (content.classList.contains('open')) {
                        content.classList.remove('open');
                        content.style.maxHeight = null;
                        icon.classList.remove('rotate-90');
                    } else {
                        // Optional: Close other open siblings at the same level
                        const parentContainer = this.closest('ul');
                        if (parentContainer) {
                            parentContainer.querySelectorAll('.tree-content.open').forEach(siblingContent => {
                                if (siblingContent !== content && content.contains(siblingContent) === false) {
                                    siblingContent.classList.remove('open');
                                    siblingContent.style.maxHeight = null;
                                    const siblingButton = siblingContent.previousElementSibling;
                                    if (siblingButton && siblingButton.classList.contains('tree-toggle')) {
                                        const siblingIcon = siblingButton.querySelector('i.fa-chevron-right');
                                        if (siblingIcon) {
                                            siblingIcon.classList.remove('rotate-90');
                                        }
                                    }
                                }
                            });
                        }

                        content.classList.add('open');
                        requestAnimationFrame(() => {
                            content.style.maxHeight = content.scrollHeight + "px";
                        });
                        icon.classList.add('rotate-90');
                    }
                }
            });
        });

        // Search functionality
        function searchAnnuaire() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const searchItems = document.querySelectorAll('.search-item');
            
            searchItems.forEach(item => {
                const searchText = item.dataset.search || '';
                const isVisible = searchText.includes(searchTerm);
                
                if (isVisible) {
                    item.classList.remove('hidden');
                    // Show parent containers
                    let parent = item.closest('.tree-content');
                    while (parent) {
                        parent.classList.remove('hidden');
                        parent = parent.parentElement.closest('.tree-content');
                    }
                } else {
                    item.classList.add('hidden');
                }
            });

            // Auto-expand results when searching
            if (searchTerm.trim() !== '') {
                document.querySelectorAll('.tree-content').forEach(content => {
                    if (!content.classList.contains('hidden')) {
                        content.classList.add('open');
                        content.style.maxHeight = content.scrollHeight + "px";
                        const button = content.previousElementSibling;
                        if (button && button.classList.contains('tree-toggle')) {
                            const icon = button.querySelector('i.fa-chevron-right');
                            if (icon) {
                                icon.classList.add('rotate-90');
                            }
                        }
                    }
                });
            }
        }

        // Expand all functionality
        function expandAll() {
            document.querySelectorAll('.tree-content').forEach(content => {
                content.classList.add('open');
                content.style.maxHeight = content.scrollHeight + "px";
                const button = content.previousElementSibling;
                if (button && button.classList.contains('tree-toggle')) {
                    const icon = button.querySelector('i.fa-chevron-right');
                    if (icon) {
                        icon.classList.add('rotate-90');
                    }
                }
            });
        }

        // Collapse all functionality
        function collapseAll() {
            document.querySelectorAll('.tree-content').forEach(content => {
                content.classList.remove('open');
                content.style.maxHeight = null;
                const button = content.previousElementSibling;
                if (button && button.classList.contains('tree-toggle')) {
                    const icon = button.querySelector('i.fa-chevron-right');
                    if (icon) {
                        icon.classList.remove('rotate-90');
                    }
                }
            });
        }

        // Clear search functionality
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.search-item').forEach(item => {
                item.classList.remove('hidden');
            });
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.glass-sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.glass-sidebar');
            const menuButton = document.querySelector('.mobile-menu-btn');
            
            if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                clearSearch();
            }
            
            if (event.ctrlKey || event.metaKey) {
                switch(event.key) {
                    case 'f':
                        event.preventDefault();
                        document.getElementById('searchInput').focus();
                        break;
                    case 'e':
                        event.preventDefault();
                        expandAll();
                        break;
                    case 'r':
                        event.preventDefault();
                        collapseAll();
                        break;
                }
            }
        });

        // Loading animation for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.division-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
        </script>
    </main>
</body>
</html>
