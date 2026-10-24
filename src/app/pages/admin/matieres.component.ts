import { Component, OnInit, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../core/api.service';
import { Classe, Enseignant, Matiere } from '../../core/models';
import { ToastService, messageErreur } from '../../core/toast.service';
import { ChargementComponent } from '../../shared/chargement.component';
import { IconeComponent } from '../../shared/icone.component';
import { ModalComponent } from '../../shared/modal.component';

@Component({
  selector: 'app-matieres',
  standalone: true,
  imports: [FormsModule, IconeComponent, ModalComponent, ChargementComponent],
  templateUrl: './matieres.component.html',
})
export class MatieresComponent implements OnInit {
  private api = inject(ApiService);
  private toast = inject(ToastService);

  matieres = signal<Matiere[] | null>(null);
  classes = signal<Classe[]>([]);
  enseignants = signal<Enseignant[]>([]);

  filtreClasse: number | null = null;
  filtreSemestre: number | null = null;

  modalOuvert = signal(false);
  enCours = signal(false);
  erreur = signal('');
  edition: Matiere | null = null;
  form: Partial<Matiere> = {};

  ngOnInit() {
    this.api.classes().subscribe((c) => this.classes.set(c));
    this.api.enseignants().subscribe((e) => this.enseignants.set(e));
    this.charger();
  }

  charger() {
    this.matieres.set(null);
    this.api.matieres({ classe_id: this.filtreClasse, semestre: this.filtreSemestre }).subscribe({
      next: (m) => this.matieres.set(m),
      error: (e) => this.toast.erreur(e),
    });
  }

  ouvrir(m?: Matiere) {
    this.edition = m ?? null;
    this.form = m
      ? { code: m.code, libelle: m.libelle, coefficient: m.coefficient, semestre: m.semestre, classe_id: m.classe_id, enseignant_id: m.enseignant_id }
      : { code: '', libelle: '', coefficient: 2, semestre: 1, classe_id: this.filtreClasse ?? this.classes()[0]?.id, enseignant_id: null };
    this.erreur.set('');
    this.modalOuvert.set(true);
  }

  enregistrer() {
    this.enCours.set(true);
    const requete = this.edition
      ? this.api.modifierMatiere(this.edition.id, this.form)
      : this.api.creerMatiere(this.form);

    requete.subscribe({
      next: () => {
        this.toast.succes(this.edition ? 'Matière modifiée.' : 'Matière ajoutée.');
        this.modalOuvert.set(false);
        this.enCours.set(false);
        this.charger();
      },
      error: (e) => { this.erreur.set(messageErreur(e)); this.enCours.set(false); },
    });
  }

  supprimer(m: Matiere) {
    if (!confirm(`Supprimer la matière « ${m.libelle} » ? Toutes ses notes seront supprimées.`)) return;
    this.api.supprimerMatiere(m.id).subscribe({
      next: () => { this.toast.succes('Matière supprimée.'); this.charger(); },
      error: (e) => this.toast.erreur(e),
    });
  }
}
