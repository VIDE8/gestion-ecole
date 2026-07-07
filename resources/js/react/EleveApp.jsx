import { useEffect, useState, useCallback } from 'react';

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

async function apiFetch(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            ...(options.headers || {}),
        },
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw { status: response.status, data };
    }

    return data;
}

function calculAge(dateNaissance) {
    const naissance = new Date(dateNaissance);
    const diff = new Date() - naissance;
    return Math.max(0, Math.floor(diff / (365.25 * 24 * 3600 * 1000)));
}

function StatutEcolage({ eleve }) {
    const totalPaye = (eleve.paiements || [])
        .filter((p) => p.type_frais === 'scolarite')
        .reduce((sum, p) => sum + Number(p.montant_verse), 0);
    const reste = 25000 - totalPaye;

    if (totalPaye >= 25000) {
        return <span className="badge bg-success text-white w-100 py-1">🟢 Soldé</span>;
    }
    if (totalPaye > 0) {
        return (
            <>
                <span className="badge bg-warning text-dark w-100 py-1">
                    🟡 Avance (-{reste.toLocaleString('fr-FR')} F)
                </span>
                <small className="d-block text-muted text-center small">
                    Payé: {totalPaye.toLocaleString('fr-FR')} F
                </small>
            </>
        );
    }
    return <span className="badge bg-danger text-white w-100 py-1">🔴 Non payé (-25 000 F)</span>;
}

export default function EleveApp() {
    const [eleves, setEleves] = useState([]);
    const [classes, setClasses] = useState([]);
    const [prochainMatricule, setProchainMatricule] = useState('');
    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [successMsg, setSuccessMsg] = useState(null);
    const [editingId, setEditingId] = useState(null);

    const [form, setForm] = useState({
        nom: '',
        prenom: '',
        date_naissance: '',
        classes_id: '',
    });
    const [editForm, setEditForm] = useState({
        nom: '',
        prenom: '',
        date_naissance: '',
        classes_id: '',
    });

    const load = useCallback(async (searchTerm = '') => {
        setLoading(true);
        setError(null);
        try {
            const qs = searchTerm ? `?search=${encodeURIComponent(searchTerm)}` : '';
            const data = await apiFetch(`/api/eleves${qs}`);
            setEleves(data.eleves);
            setClasses(data.classes);
            setProchainMatricule(data.prochain_matricule);
        } catch (e) {
            setError("Impossible de charger le registre des élèves.");
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        load();
    }, [load]);

    function handleSearchSubmit(e) {
        e.preventDefault();
        load(search);
    }

    async function handleCreate(e) {
        e.preventDefault();
        setError(null);
        setSuccessMsg(null);
        try {
            const data = await apiFetch('/api/eleves', {
                method: 'POST',
                body: JSON.stringify({ ...form, matricule: prochainMatricule }),
            });
            setSuccessMsg(data.message);
            setForm({ nom: '', prenom: '', date_naissance: '', classes_id: '' });
            load(search);
        } catch (e) {
            const msg = e?.data?.message || "Erreur lors de l'inscription de l'élève.";
            setError(msg);
        }
    }

    function startEdit(eleve) {
        setEditingId(eleve.id);
        setEditForm({
            nom: eleve.nom,
            prenom: eleve.prenom,
            date_naissance: eleve.date_naissance,
            classes_id: eleve.classes_id,
        });
    }

    async function handleUpdate(e, id) {
        e.preventDefault();
        setError(null);
        setSuccessMsg(null);
        try {
            const data = await apiFetch(`/api/eleves/${id}`, {
                method: 'PUT',
                body: JSON.stringify(editForm),
            });
            setSuccessMsg(data.message);
            setEditingId(null);
            load(search);
        } catch (e) {
            setError("Erreur lors de la modification de l'élève.");
        }
    }

    async function handleDelete(id) {
        if (!window.confirm('Êtes-vous certain de vouloir supprimer cet élève ? Cette action effacera également ses notes et paiements.')) {
            return;
        }
        setError(null);
        setSuccessMsg(null);
        try {
            const data = await apiFetch(`/api/eleves/${id}`, { method: 'DELETE' });
            setSuccessMsg(data.message);
            load(search);
        } catch (e) {
            setError("Erreur lors de la suppression de l'élève.");
        }
    }

    return (
        <div className="container my-4">
            <div className="row mb-4">
                <div className="col-md-8 mx-auto">
                    <div className="card shadow-sm border-0">
                        <div className="card-body">
                            <form onSubmit={handleSearchSubmit} className="d-flex gap-2">
                                <input
                                    type="text"
                                    className="form-control form-control-lg"
                                    placeholder="Rechercher par nom, prénom ou numéro..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                />
                                <button type="submit" className="btn btn-primary px-4 fw-bold">
                                    Rechercher
                                </button>
                                {search && (
                                    <button
                                        type="button"
                                        className="btn btn-outline-secondary"
                                        onClick={() => {
                                            setSearch('');
                                            load('');
                                        }}
                                    >
                                        Effacer les filtres
                                    </button>
                                )}
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {error && <div className="alert alert-danger py-2">{error}</div>}
            {successMsg && <div className="alert alert-success py-2">{successMsg}</div>}

            <div className="row">
                <div className="col-md-4 mb-4">
                    <div className="card shadow-sm border-0">
                        <div className="card-body">
                            <h5 className="card-title fw-bold text-primary mb-3">Inscrire un Élève (React)</h5>

                            <form onSubmit={handleCreate}>
                                <div className="mb-3">
                                    <label className="form-label small fw-bold">Nom</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="ex: KOFFI"
                                        required
                                        value={form.nom}
                                        onChange={(e) => setForm({ ...form, nom: e.target.value })}
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small fw-bold">Prénom</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="ex: Yao"
                                        required
                                        value={form.prenom}
                                        onChange={(e) => setForm({ ...form, prenom: e.target.value })}
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small fw-bold">Date de naissance</label>
                                    <input
                                        type="date"
                                        className="form-control"
                                        required
                                        value={form.date_naissance}
                                        onChange={(e) => setForm({ ...form, date_naissance: e.target.value })}
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small fw-bold">Matricule (Automatique)</label>
                                    <input
                                        type="text"
                                        className="form-control bg-light fw-bold text-secondary"
                                        value={prochainMatricule}
                                        readOnly
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small fw-bold">Classe d'affectation</label>
                                    <select
                                        className="form-select"
                                        required
                                        value={form.classes_id}
                                        onChange={(e) => setForm({ ...form, classes_id: e.target.value })}
                                    >
                                        <option value="">Choisir une classe...</option>
                                        {classes.map((c) => (
                                            <option key={c.id} value={c.id}>
                                                {c.niveau} - {c.nom_classe}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <button type="submit" className="btn btn-primary w-100 fw-bold text-white">
                                    Inscrire l'élève
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div className="col-md-8">
                    <div className="card shadow-sm border-0">
                        <div className="card-body">
                            <h5 className="card-title fw-bold mb-3">
                                Registre Général des Élèves ({eleves.length})
                                <span className="badge bg-primary ms-2">React</span>
                            </h5>

                            {loading ? (
                                <p className="text-muted small">Chargement...</p>
                            ) : (
                                <div className="table-responsive" style={{ maxHeight: 550, overflowY: 'auto' }}>
                                    <table className="table table-hover align-middle">
                                        <thead className="table-light sticky-top">
                                            <tr>
                                                <th>Matricule</th>
                                                <th>Nom &amp; Prénom</th>
                                                <th>Classe / Âge</th>
                                                <th>Statut Écolage</th>
                                                <th className="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {eleves.length === 0 && (
                                                <tr>
                                                    <td colSpan={5} className="text-center text-muted small py-4">
                                                        Aucun élève trouvé.
                                                    </td>
                                                </tr>
                                            )}

                                            {eleves.map((eleve) =>
                                                editingId === eleve.id ? (
                                                    <tr key={eleve.id}>
                                                        <td colSpan={5}>
                                                            <form
                                                                onSubmit={(e) => handleUpdate(e, eleve.id)}
                                                                className="d-flex flex-wrap gap-2 align-items-end py-2"
                                                            >
                                                                <div>
                                                                    <label className="form-label small fw-bold mb-0">Nom</label>
                                                                    <input
                                                                        className="form-control form-control-sm"
                                                                        value={editForm.nom}
                                                                        onChange={(e) => setEditForm({ ...editForm, nom: e.target.value })}
                                                                        required
                                                                    />
                                                                </div>
                                                                <div>
                                                                    <label className="form-label small fw-bold mb-0">Prénom</label>
                                                                    <input
                                                                        className="form-control form-control-sm"
                                                                        value={editForm.prenom}
                                                                        onChange={(e) => setEditForm({ ...editForm, prenom: e.target.value })}
                                                                        required
                                                                    />
                                                                </div>
                                                                <div>
                                                                    <label className="form-label small fw-bold mb-0">Naissance</label>
                                                                    <input
                                                                        type="date"
                                                                        className="form-control form-control-sm"
                                                                        value={editForm.date_naissance}
                                                                        onChange={(e) => setEditForm({ ...editForm, date_naissance: e.target.value })}
                                                                        required
                                                                    />
                                                                </div>
                                                                <div>
                                                                    <label className="form-label small fw-bold mb-0">Classe</label>
                                                                    <select
                                                                        className="form-select form-select-sm"
                                                                        value={editForm.classes_id}
                                                                        onChange={(e) => setEditForm({ ...editForm, classes_id: e.target.value })}
                                                                        required
                                                                    >
                                                                        {classes.map((c) => (
                                                                            <option key={c.id} value={c.id}>
                                                                                {c.niveau} - {c.nom_classe}
                                                                            </option>
                                                                        ))}
                                                                    </select>
                                                                </div>
                                                                <button type="submit" className="btn btn-sm btn-success fw-bold">
                                                                    Enregistrer
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    className="btn btn-sm btn-outline-secondary fw-bold"
                                                                    onClick={() => setEditingId(null)}
                                                                >
                                                                    Annuler
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                ) : (
                                                    <tr key={eleve.id}>
                                                        <td className="text-muted small fw-bold">{eleve.matricule}</td>
                                                        <td className="fw-bold text-uppercase">
                                                            {eleve.nom} <span className="text-capitalize fw-normal">{eleve.prenom}</span>
                                                        </td>
                                                        <td>
                                                            <span className="badge bg-success text-white px-2 py-1">
                                                                {eleve.classe?.niveau ?? 'N/A'}
                                                            </span>
                                                            <small className="d-block text-muted">{calculAge(eleve.date_naissance)} ans</small>
                                                        </td>
                                                        <td>
                                                            <StatutEcolage eleve={eleve} />
                                                        </td>
                                                        <td className="text-end">
                                                            <div className="d-flex gap-1 justify-content-end">
                                                                <button
                                                                    className="btn btn-sm btn-outline-primary py-0 px-2 fw-bold"
                                                                    onClick={() => startEdit(eleve)}
                                                                >
                                                                    Modifier
                                                                </button>
                                                                <button
                                                                    className="btn btn-sm btn-outline-danger py-0 px-2 fw-bold"
                                                                    onClick={() => handleDelete(eleve.id)}
                                                                >
                                                                    Supprimer
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                )
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
