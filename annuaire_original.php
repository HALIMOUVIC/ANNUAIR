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
        /* Custom scrollbar for a more modern look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #e0f2f7; /* Light blue track */
        }
        ::-webkit-scrollbar-thumb {
            background: #90cdf4; /* Medium blue thumb */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #63b3ed; /* Darker blue on hover */
        }

        /* Treeview content styling for smooth animation */
        .tree-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out; /* Smooth collapse */
        }
        .tree-content.open {
            max-height: 1000px; /* A sufficiently large value, adjusted dynamically by JS */
            transition: max-height 0.5s ease-in; /* Smooth expand */
        }

        /* Visual lines for hierarchy */
        .tree-branch-line {
            border-left: 1px solid #cbd5e1; /* A subtle light gray line */
            margin-left: 20px; /* Space for the line and padding */
            padding-left: 20px; /* Indentation for nested items */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-200 min-h-screen flex font-sans antialiased">
    <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-700 text-white flex flex-col py-8 px-4 shadow-2xl min-h-screen fixed left-0 top-0 bottom-0 z-20">
        <div class="flex flex-col items-center mb-10">
            <div class="bg-blue-300 rounded-full p-4 mb-3 shadow-lg">
                <i class="fa fa-sitemap text-blue-800 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-center tracking-wide">Annuaire PRO</h2>
        </div>
        <nav class="flex flex-col gap-3">
            <a href="index.php" class="flex items-center gap-3 px-4 py-2 rounded-lg text-blue-100 hover:bg-blue-600 transition-all duration-300 ease-in-out group">
                <i class="fa fa-plus text-lg group-hover:scale-110 transition-transform"></i>
                <span class="text-lg">Ajouter une division</span>
            </a>
            <a href="annuaire.php" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-600 text-white shadow-md">
                <i class="fa fa-sitemap text-lg"></i>
                <span class="text-lg">Annuaire</span>
            </a>
        </nav>
        <div class="mt-auto text-xs text-center text-blue-300 pt-8 border-t border-blue-600 mx-4 pt-4">Annuaire &copy; 2025</div>
    </aside>
    <main class="flex-1 flex flex-col items-center py-8 ml-64 w-full min-w-0">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl p-10 border border-blue-100 transform hover:shadow-2xl transition-all duration-300 ease-in-out">
            <div class="flex flex-col items-center mb-12">
                <div class="bg-blue-600 rounded-full p-6 mb-4 shadow-lg">
                    <i class="fa fa-sitemap text-white text-5xl"></i>
                </div>
                <h1 class="text-5xl font-extrabold text-center text-blue-800 tracking-tight leading-tight mb-2">Annuaire d'Entreprise</h1>
                <p class="text-gray-500 text-center mt-2 text-xl font-light">Explorez l'organisation et les contacts de notre annuaire interactif.</p>
            </div>
            <ul id="tree-root" class="space-y-4">
            <?php foreach ($filtered as $i => $div): ?>
                <li class="bg-blue-50 rounded-lg p-4 shadow-sm border border-blue-100">
                    <button type="button" class="tree-toggle flex items-center justify-between w-full text-left font-bold text-blue-700 hover:text-blue-900 focus:outline-none" data-target="div-content-<?= $i ?>">
                        <span class="text-2xl flex items-center"><i class="fa fa-building text-blue-600 mr-3"></i><?= htmlspecialchars($div['division']) ?></span>
                        <i class="fa fa-chevron-right text-blue-500 transition-transform duration-300"></i>
                    </button>
                    <div id="div-content-<?= $i ?>" class="tree-content">
                        <div class="py-3 tree-branch-line">
                            <ul class="space-y-2">
                                <li><p class="text-gray-700"><span class="font-semibold"><i class="fa fa-user-tie mr-2 text-blue-400"></i>Chef division :</span> <?= htmlspecialchars($div['chef']) ?> <?php if($div['phone']): ?><span class="text-gray-600 text-sm ml-3"><i class="fa fa-phone text-blue-500 mr-2"></i><?= htmlspecialchars($div['phone']) ?></span><?php endif; ?></p></li>
                                <?php if($div['secretariat']): ?><li><p class="text-gray-700"><span class="font-semibold"><i class="fa fa-user-secret mr-2 text-blue-400"></i>Secrétariat :</span> <?= htmlspecialchars($div['secretariat']) ?></p></li><?php endif; ?>
                                <?php if($div['ord']): ?><li><p class="text-gray-700"><span class="font-semibold"><i class="fa fa-clipboard-list mr-2 text-blue-400"></i>Ordonnancement :</span> <?= htmlspecialchars($div['ord']) ?></p></li><?php endif; ?>

                                <?php if (!empty($div['departements'])): ?>
                                    <li class="mt-4">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2 flex items-center"><i class="fa fa-cubes mr-3 text-blue-500"></i>Services :</h3>
                                        <ul class="space-y-2 tree-branch-line">
                                        <?php foreach ($div['departements'] as $j => $dep): ?>
                                            <li>
                                                <button type="button" class="tree-toggle flex items-center justify-between w-full text-left text-blue-700 hover:text-blue-800 focus:outline-none py-2" data-target="dep-content-<?= $i ?>-<?= $j ?>">
                                                    <span class="text-lg font-semibold flex items-center"><i class="fa fa-briefcase text-blue-400 mr-3"></i><?= htmlspecialchars($dep['name']) ?></span>
                                                    <i class="fa fa-chevron-right text-gray-400 transition-transform duration-300"></i>
                                                </button>
                                                <div id="dep-content-<?= $i ?>-<?= $j ?>" class="tree-content">
                                                    <div class="py-2 text-gray-800 tree-branch-line">
                                                        <ul class="space-y-1">
                                                            <?php if($dep['phone']): ?><li><p class="text-base"><span class="font-medium"><i class="fa fa-phone text-blue-400 mr-2"></i>Téléphone :</span> <?= htmlspecialchars($dep['phone']) ?></p></li><?php endif; ?>
                                                            <?php if (!empty($dep['sections'])): ?>
                                                                <li class="mt-3">
                                                                    <p class="font-semibold text-base mb-1 flex items-center"><i class="fa fa-sitemap mr-2 text-gray-500"></i>Sections :</p>
                                                                    <ul class="ml-4 space-y-1">
                                                                        <?php foreach ($dep['sections'] as $sec): ?>
                                                                            <li class="flex items-center text-sm py-1">
                                                                                <i class="fa fa-angle-right text-gray-400 mr-2"></i>
                                                                                <span class="font-medium"><?= htmlspecialchars($sec['name']) ?></span>
                                                                                <?php if($sec['phone']): ?><span class="text-gray-500 ml-3"><i class="fa fa-phone text-blue-400 mr-1"></i><?= htmlspecialchars($sec['phone']) ?></span><?php endif; ?>
                                                                                <?php if(!empty($sec['phone2'])): ?><span class="text-gray-400 ml-2"><i class="fa fa-phone text-blue-300 mr-1"></i><?= htmlspecialchars($sec['phone2']) ?></span><?php endif; ?>
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
        // Gestion du treeview interactif
        document.querySelectorAll('.tree-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const content = document.getElementById(targetId);
                const icon = this.querySelector('i.fa-chevron-right'); // Ensure targeting the correct icon

                if (content) {
                    if (content.classList.contains('open')) {
                        content.classList.remove('open');
                        content.style.maxHeight = null; // Collapse
                        icon.classList.remove('rotate-90');
                    } else {
                        // Optional: Close other open siblings at the same level
                        // This helps keep the view clean by only having one branch open per level
                        const parentContainer = this.closest('ul');
                        if (parentContainer) { // Check if parentContainer exists (e.g., for top-level divisions)
                            parentContainer.querySelectorAll('.tree-content.open').forEach(siblingContent => {
                                // Ensure we're only closing siblings, not descendants of other siblings or self
                                if (siblingContent !== content && content.contains(siblingContent) === false) {
                                    siblingContent.classList.remove('open');
                                    siblingContent.style.maxHeight = null;
                                    // Find the corresponding icon for the sibling button
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
                        // Calculate scrollHeight dynamically for accurate expansion
                        // A small timeout ensures rendering before calculation if content is complex
                        requestAnimationFrame(() => {
                            content.style.maxHeight = content.scrollHeight + "px";
                        });
                        icon.classList.add('rotate-90');
                    }
                }
            });
        });
        </script>
    </main>
</body>
</html>
