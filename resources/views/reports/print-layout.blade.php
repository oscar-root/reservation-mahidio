<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport_Audit_Mahidio_{{ now()->format('d_m_Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        .font-serif { font-family: 'Cormorant Garamond', serif; }

        @media print {
            .no-print { display: none !important; }
            @page { size: A4; margin: 15mm; }
            body { background: white !important; padding: 0 !important; }
            .document-container { 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 !important; 
                width: 100% !important;
            }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-0 md:p-12">

    <!-- BARRE D'ACTION (Masquée à l'impression) -->
    <div class="no-print max-w-5xl mx-auto mb-10 flex justify-between items-center bg-slate-900 p-6 rounded-[2rem] shadow-2xl">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-[#d4af37] rounded-xl flex items-center justify-center text-black font-black">M</div>
            <div>
                <h1 class="text-white font-bold text-sm">Aperçu du Rapport Officiel</h1>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">Prêt pour génération PDF / Impression</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.history.back()" class="px-6 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">Annuler</button>
            <button onclick="window.print()" class="px-6 py-2 bg-[#d4af37] text-black rounded-xl text-xs font-black uppercase tracking-widest shadow-lg hover:scale-105 transition">Imprimer maintenant</button>
        </div>
    </div>

    <!-- FEUILLE A4 -->
    <div class="document-container max-w-[210mm] mx-auto bg-white p-16 shadow-2xl border border-slate-200 min-h-[297mm] flex flex-col">
        
        <!-- 1. EN-TÊTE CORPORATE -->
        <div class="flex justify-between items-start border-b-4 border-slate-900 pb-10 mb-10">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter text-slate-900 mb-1">Lycée Mahidio</h1>
                <p class="text-sm font-bold text-indigo-600 uppercase tracking-[0.3em]">Congrégation Religieuse</p>
                <p class="text-[10px] text-slate-500 mt-2 font-bold uppercase tracking-widest">Service de Gestion Événementielle • Kamina, RDC</p>
            </div>
            <div class="text-right">
                <div class="bg-slate-900 text-[#d4af37] px-6 py-3 rounded-2xl mb-3 inline-block shadow-lg">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em]">Rapport d'Audit Financier</h2>
                </div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Édité le : {{ now()->translatedFormat('d F Y à H:i') }}</p>
            </div>
        </div>

        <!-- 2. RÉSUMÉ DES INDICATEURS CLÉS -->
        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Chiffre d'Affaires Global</p>
                <p class="text-3xl font-black text-slate-900">{{ number_format($caTotal, 0, ',', ' ') }} <span class="text-sm text-indigo-600">$</span></p>
            </div>
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Dossiers Finalisés</p>
                <p class="text-3xl font-black text-slate-900">{{ $transactions->count() }}</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl">
                <p class="text-[9px] font-black text-[#d4af37] uppercase tracking-widest mb-2">Statut Système</p>
                <p class="text-xl font-black uppercase tracking-tighter italic">Certifié Conforme</p>
            </div>
        </div>

        <!-- 3. ANALYSE ANALYTIQUE PAR CATÉGORIE -->
        <div class="mb-12">
            <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900 mb-6 flex items-center gap-3">
                <span class="w-8 h-1 bg-[#d4af37]"></span>
                Répartition des revenus par activité
            </h3>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase border-b-2 border-slate-100">
                        <th class="py-4">Type de Cérémonie</th>
                        <th class="py-4 text-center">Volume</th>
                        <th class="py-4 text-right">Montant Cumulé</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach($categories as $cat)
                    <tr>
                        <td class="py-4 font-bold text-slate-700 uppercase tracking-tight">{{ $cat->type_ceremonie }}</td>
                        <td class="py-4 text-center text-slate-500 font-bold">{{ $cat->nb }}</td>
                        <td class="py-4 text-right font-black text-slate-900">{{ number_format($cat->total, 0) }} $</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 4. JOURNAL DÉTAILLÉ DES ENCAISSEMENTS -->
        <div class="mb-12 flex-1">
            <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900 mb-6 flex items-center gap-3">
                <span class="w-8 h-1 bg-indigo-600"></span>
                Extrait du journal de caisse
            </h3>
            <table class="w-full text-[9px] border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-bold uppercase tracking-widest">
                        <th class="p-3 text-left">Date & Heure</th>
                        <th class="p-3 text-left">Réf. Reçu</th>
                        <th class="p-3 text-left">Bénéficiaire</th>
                        <th class="p-3 text-right">Valeur Nette</th>
                    </tr>
                </thead>
                <tbody class="border border-slate-100">
                    @foreach($transactions as $t)
                    <tr class="border-b border-slate-100">
                        <td class="p-3 text-slate-500">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3 font-bold text-indigo-600 italic">#REC-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3 font-black text-slate-800 uppercase tracking-tighter">{{ $t->nom_client }}</td>
                        <td class="p-3 text-right font-black text-slate-900">{{ number_format($t->montant, 0) }} $</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 5. ZONE DE CERTIFICATION (PIED DE PAGE) -->
        <div class="mt-auto pt-10 border-t border-slate-200">
            <div class="grid grid-cols-2 gap-20">
                <div class="text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-16 tracking-widest">Sceau de la Congrégation</p>
                    <div class="w-32 h-1 border-b border-slate-100 mx-auto opacity-50"></div>
                </div>
                <div class="text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-16 tracking-widest">Le Responsable des Finances</p>
                    <div class="w-48 h-0.5 bg-slate-900 mx-auto mb-2"></div>
                    <p class="text-[10px] font-black text-slate-900 uppercase italic underline decoration-[#d4af37]">{{ Auth::user()->name }}</p>
                </div>
            </div>
            <div class="mt-16 text-center">
                <div class="inline-block border-2 border-slate-100 px-6 py-2 rounded-full">
                    <p class="text-[7px] text-slate-300 uppercase tracking-[0.6em] font-black">
                        Generated by Mahidio Digital ERP • Security Protocol Active
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>