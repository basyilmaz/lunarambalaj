<?php

namespace App\Filament\Pages;

use App\Models\Lead;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class LeadPipeline extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Satis ve Talepler';

    protected static ?string $navigationLabel = 'Lead Pipeline';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.lead-pipeline';

    /**
     * @var array<string, array{label:string,color:string}>
     */
    public array $statuses = [
        'new' => ['label' => 'Yeni', 'color' => 'warning'],
        'read' => ['label' => 'Okundu', 'color' => 'info'],
        'replied' => ['label' => 'Yanitlandi', 'color' => 'success'],
        'archived' => ['label' => 'Arsiv', 'color' => 'gray'],
    ];

    /**
     * @var array<string, array<int, array<string, mixed>>>
     */
    public array $columns = [];

    public function mount(): void
    {
        $this->refreshBoard();
    }

    public function moveLead(int $leadId, string $toStatus): void
    {
        if (! array_key_exists($toStatus, $this->statuses)) {
            return;
        }

        $lead = Lead::query()->find($leadId);
        if (! $lead) {
            return;
        }

        $lead->update(['status' => $toStatus]);
        $this->refreshBoard();

        Notification::make()
            ->title('Lead durumu guncellendi')
            ->body("#{$lead->id} -> " . $this->statuses[$toStatus]['label'])
            ->success()
            ->send();
    }

    protected function refreshBoard(): void
    {
        $query = Lead::query()
            ->with('assignee')
            ->whereIn('status', array_keys($this->statuses))
            ->orderByDesc('created_at')
            ->limit(400);

        /** @var Collection<int, Lead> $leads */
        $leads = $query->get();

        $columns = [];
        foreach (array_keys($this->statuses) as $status) {
            $columns[$status] = [];
        }

        foreach ($leads as $lead) {
            $status = in_array($lead->status, array_keys($this->statuses), true) ? $lead->status : 'new';
            $columns[$status][] = [
                'id' => $lead->id,
                'type' => $lead->type,
                'name' => $lead->name,
                'company' => $lead->company,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'quantity' => (int) data_get($lead->meta, 'quantity', 0),
                'product_category' => (string) data_get($lead->meta, 'product_category', ''),
                'assignee' => $lead->assignee?->name,
                'created_at' => optional($lead->created_at)->format('d.m.Y H:i'),
            ];
        }

        $this->columns = $columns;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null && ($user->isAdmin() || $user->isEditor() || $user->isMarketingManager());
    }
}
