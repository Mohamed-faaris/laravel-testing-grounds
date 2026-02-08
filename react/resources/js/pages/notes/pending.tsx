import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { CheckCircle, XCircle, Clock } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import { index, approve, reject } from '@/routes/notes';
import type { BreadcrumbItem } from '@/types';
import { useState } from 'react';

interface Note {
    id: number;
    title: string;
    content: string;
    created_at: string;
    user?: {
        name: string;
        email: string;
    };
}

interface NotesResponse {
    data: Note[];
    links: {
        next: string | null;
        prev: string | null;
    };
}

interface Props {
    notes: NotesResponse;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notes',
        href: index().url,
    },
    {
        title: 'Pending Review',
        href: '#',
    },
];

export default function PendingReview({ notes }: Props) {
    const [reviewNotes, setReviewNotes] = useState<Record<number, string>>({});
    const [selectedNote, setSelectedNote] = useState<Note | null>(null);
    const [dialogOpen, setDialogOpen] = useState<Record<number, boolean>>({});

    const handleApprove = (noteId: number) => {
        router.post(approve({ note: noteId }).url, {
            review_notes: reviewNotes[noteId] || '',
        });
    };

    const handleReject = (noteId: number) => {
        router.post(reject({ note: noteId }).url, {
            review_notes: reviewNotes[noteId],
        });
    };

    const openDialog = (noteId: number) => {
        setDialogOpen({ ...dialogOpen, [noteId]: true });
    };

    const closeDialog = (noteId: number) => {
        setDialogOpen({ ...dialogOpen, [noteId]: false });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pending Review" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">Pending Review</h1>
                        <p className="text-muted-foreground">
                            Review and approve notes for publication
                        </p>
                    </div>
                    <Badge
                        variant="secondary"
                        className="flex items-center gap-1"
                    >
                        <Clock className="h-3 w-3" />
                        {notes.data.length} pending
                    </Badge>
                </div>

                {notes.data.length === 0 ? (
                    <Card>
                        <CardContent className="py-12 text-center">
                            <CheckCircle className="mx-auto mb-4 h-12 w-12 text-green-500" />
                            <p className="text-lg font-medium">
                                All caught up!
                            </p>
                            <p className="text-sm text-muted-foreground">
                                No notes pending review at the moment.
                            </p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4">
                        {notes.data.map((note) => (
                            <Card key={note.id} className="overflow-hidden">
                                <CardHeader className="bg-muted/50">
                                    <div className="flex items-start justify-between">
                                        <div>
                                            <CardTitle>{note.title}</CardTitle>
                                            <CardDescription className="mt-1">
                                                Submitted by {note.user?.name} (
                                                {note.user?.email}) •{' '}
                                                {new Date(
                                                    note.created_at,
                                                ).toLocaleDateString()}
                                            </CardDescription>
                                        </div>
                                        <Badge
                                            variant="outline"
                                            className="flex items-center gap-1"
                                        >
                                            <Clock className="h-3 w-3" />
                                            Pending
                                        </Badge>
                                    </div>
                                </CardHeader>
                                <CardContent className="pt-6">
                                    <div className="mb-6 rounded-md border bg-muted/30 p-4">
                                        <h4 className="mb-2 text-sm font-medium">
                                            Content:
                                        </h4>
                                        <p className="text-sm whitespace-pre-wrap text-muted-foreground">
                                            {note.content}
                                        </p>
                                    </div>

                                    <div className="space-y-4">
                                        <div>
                                            <label className="mb-2 block text-sm font-medium">
                                                Review Notes (optional for
                                                approval, required for
                                                rejection):
                                            </label>
                                            <Textarea
                                                placeholder="Add your review comments here..."
                                                value={
                                                    reviewNotes[note.id] || ''
                                                }
                                                onChange={(e) =>
                                                    setReviewNotes({
                                                        ...reviewNotes,
                                                        [note.id]:
                                                            e.target.value,
                                                    })
                                                }
                                                rows={3}
                                            />
                                        </div>

                                        <div className="flex gap-2">
                                            <Button
                                                variant="default"
                                                className="flex items-center gap-2"
                                                onClick={() =>
                                                    handleApprove(note.id)
                                                }
                                            >
                                                <CheckCircle className="h-4 w-4" />
                                                Approve & Publish
                                            </Button>

                                            <Dialog
                                                open={
                                                    dialogOpen[note.id] || false
                                                }
                                                onOpenChange={(open) =>
                                                    open
                                                        ? openDialog(note.id)
                                                        : closeDialog(note.id)
                                                }
                                            >
                                                <DialogTrigger asChild>
                                                    <Button
                                                        variant="destructive"
                                                        className="flex items-center gap-2"
                                                    >
                                                        <XCircle className="h-4 w-4" />
                                                        Reject
                                                    </Button>
                                                </DialogTrigger>
                                                <DialogContent>
                                                    <DialogHeader>
                                                        <DialogTitle>
                                                            Reject Note
                                                        </DialogTitle>
                                                        <DialogDescription>
                                                            Please provide
                                                            feedback explaining
                                                            why this note is
                                                            being rejected. This
                                                            will help the author
                                                            improve.
                                                        </DialogDescription>
                                                    </DialogHeader>
                                                    <div className="py-4">
                                                        <Textarea
                                                            placeholder="Explain why this note is rejected..."
                                                            value={
                                                                reviewNotes[
                                                                    note.id
                                                                ] || ''
                                                            }
                                                            onChange={(e) =>
                                                                setReviewNotes({
                                                                    ...reviewNotes,
                                                                    [note.id]:
                                                                        e.target
                                                                            .value,
                                                                })
                                                            }
                                                            rows={4}
                                                        />
                                                        {!reviewNotes[
                                                            note.id
                                                        ] && (
                                                            <p className="mt-2 text-sm text-red-500">
                                                                Review notes are
                                                                required for
                                                                rejection
                                                            </p>
                                                        )}
                                                    </div>
                                                    <DialogFooter>
                                                        <Button
                                                            variant="outline"
                                                            onClick={() =>
                                                                closeDialog(
                                                                    note.id,
                                                                )
                                                            }
                                                        >
                                                            Cancel
                                                        </Button>
                                                        <Button
                                                            variant="destructive"
                                                            onClick={() => {
                                                                if (
                                                                    reviewNotes[
                                                                        note.id
                                                                    ]
                                                                ) {
                                                                    handleReject(
                                                                        note.id,
                                                                    );
                                                                    closeDialog(
                                                                        note.id,
                                                                    );
                                                                }
                                                            }}
                                                            disabled={
                                                                !reviewNotes[
                                                                    note.id
                                                                ]
                                                            }
                                                        >
                                                            Confirm Rejection
                                                        </Button>
                                                    </DialogFooter>
                                                </DialogContent>
                                            </Dialog>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}

                {(notes.links.next || notes.links.prev) && (
                    <div className="flex justify-center gap-4">
                        {notes.links.prev && (
                            <Button variant="outline" asChild>
                                <Link href={notes.links.prev}>Previous</Link>
                            </Button>
                        )}
                        {notes.links.next && (
                            <Button variant="outline" asChild>
                                <Link href={notes.links.next}>Next</Link>
                            </Button>
                        )}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
