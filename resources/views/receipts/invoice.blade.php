<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu Mahidio #{{ $reservation->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> @media print { .no-print { display: none; } } </style>
</head>
<body class="bg-slate-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-12 shadow-sm border border-slate-200 rounded-lg">
        <div class="flex justify-between items-start border-b pb-8 mb-8">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tighter">Lycée Mahidio</h1>
                <p class="text-xs text-slate-500 uppercase">Congrégation Religieuse - Kamina</p>
                <p class="text-xs text-slate-500">Service de Gestion des Salles</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black text-indigo-600 uppercase">Reçu Officiel</h2>
                <p class="text-sm font-bold">N° REC-{{ $reservation->recu->id }}</p>
            </div>
        </div>

        <div class="space-y-6 mb-10">
            <div class="flex justify-between">
                <span class="text-slate-500 italic">Client :</span>
                <span class="font-bold">{{ $reservation->client->nom }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 italic">Type de Cérémonie :</span>
                <span class="font-bold">{{ $reservation->type_ceremonie }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 italic">Date de l'événement :</span>
                <span class="font-bold">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-xl flex justify-between items-center mb-10">
            <span class="font-bold text-slate-700 uppercase text-xs">Montant Total Payé</span>
            <span class="text-3xl font-black text-slate-900">{{ number_format($reservation->montant_total, 0, ',', ' ') }} $</span>
        </div>

        <div class="flex justify-between text-[10px] text-slate-400 uppercase font-bold">
            <p>Fait à Kamina, le {{ now()->format('d/m/Y') }}</p>
            <p>Signature & Sceau</p>
        </div>

        <div class="mt-12 no-print flex gap-4">
            <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-lg font-bold">Imprimer</button>
            <button onclick="window.history.back()" class="text-slate-500">Retour</button>
        </div>
    </div>
</body>
</html>