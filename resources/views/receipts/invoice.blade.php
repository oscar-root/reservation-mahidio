<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recu_#{{ $reservation->recu->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        @media print {
            .no-print { display: none !important; }
            @page { size: auto; margin: 5mm; }
            body { background: white !important; padding: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .receipt-box { 
                box-shadow: none !important; 
                border: 1px solid #f1f5f9 !important;
                margin: 0 auto !important;
                width: 320px !important; /* Largeur réduite */
                border-radius: 1rem !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 flex flex-col items-center p-4">

    <!-- BOUTONS CACHÉS À L'IMPRESSION -->
    <div class="no-print flex gap-2 mb-4">
        <button onclick="window.history.back()" class="bg-white text-slate-600 px-4 py-1.5 rounded-lg font-bold border border-slate-200 text-xs transition hover:bg-slate-50">← Retour</button>
        <button onclick="window.print()" class="bg-slate-900 text-white px-5 py-1.5 rounded-lg font-bold text-xs shadow-lg transition hover:bg-indigo-600">Imprimer</button>
    </div>

    <!-- LE REÇU COMPACTÉ -->
    <div class="receipt-box w-full max-w-[340px] bg-white shadow-2xl rounded-[1.5rem] overflow-hidden border border-slate-200">
        
        <!-- Entête Très Compacte -->
        <div class="bg-slate-900 px-6 py-4 text-center text-white">
            <div class="flex items-center justify-center gap-2 mb-0.5">
                <div class="w-6 h-6 bg-[#d4af37] rounded flex items-center justify-center text-black font-black text-[10px]">M</div>
                <h1 class="text-sm font-black uppercase tracking-tight">Salle Mahidio</h1>
            </div>
            <p class="text-[7px] uppercase tracking-[0.2em] text-slate-400">Kamina • Haut-Lomami • RDC</p>
        </div>

        <!-- Corps du Reçu -->
        <div class="p-5 space-y-3">
            <!-- Référence et Date sur une seule ligne -->
            <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-2">
                <span class="mono font-bold text-indigo-600 text-[10px]">#REC-{{ str_pad($reservation->recu->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-[9px] font-bold text-slate-400">{{ $reservation->recu->created_at->format('d/m/Y H:i') }}</span>
            </div>

            <!-- Infos Client & Événement en grille compacte -->
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                <div class="col-span-2">
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Bénéficiaire</p>
                    <p class="text-xs font-black text-slate-800 uppercase truncate">{{ $reservation->client->nom }}</p>
                </div>
                
                <div>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Cérémonie</p>
                    <p class="text-[10px] font-bold text-slate-700">{{ $reservation->type_ceremonie }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Durée</p>
                    <p class="text-[10px] font-bold text-slate-700">{{ $reservation->duree }}</p>
                </div>
                
                <div class="col-span-2">
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Date de l'événement</p>
                    <p class="text-[10px] font-bold text-slate-700">{{ \Carbon\Carbon::parse($reservation->date_debut)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <!-- Finance & QR (Plus serré) -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Total Net Payé</p>
                    <p class="text-xl font-black text-slate-900">
                        {{ number_format($reservation->montant_total, 0, ',', ' ') }} <span class="text-xs text-indigo-600 font-bold">$</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-white p-1 rounded-lg border border-slate-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=MAHIDIO-{{ $reservation->recu->id }}" class="w-full h-full">
                </div>
            </div>

            <!-- Signature & Agent -->
            <div class="pt-2 border-t border-slate-100">
                <div class="flex justify-between items-end">
                    <div class="text-[7px] text-slate-400 uppercase font-bold leading-tight">
                        <p>Agent : {{ $reservation->nom_agent }}</p>
                        <p class="mt-0.5">Merci de votre confiance.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-14 border-b border-slate-200 mb-0.5"></div>
                        <p class="text-[6px] font-black uppercase text-slate-400 tracking-tighter">Sceau & Signature</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Noir mince -->
        <div class="bg-slate-900 py-1.5 text-center">
            <p class="text-[6px] text-slate-500 uppercase tracking-[0.3em] font-black italic">MAHIDIO DIGITAL CERTIFIED</p>
        </div>
    </div>

</body>
</html>