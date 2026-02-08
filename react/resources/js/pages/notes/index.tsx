import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { create, destroy, edit, index, show } from '@/routes/notes';
import type { BreadcrumbItem } from '@/types';

interface Note {
    id: number;
    title: string;
    content: string;
    created_at: string;
    user?: {
        name: string;
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
    auth: {
        user: {
            role: string;
        };
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notes',
        href: index().url,
    },
];

export default function NotesIndex({ notes, auth }: Props) {
    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this note?')) {
            router.delete(destroy({ note: id }).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Notes" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">Notes</h1>
                        <p className="text-muted-foreground">
                            {auth.user.role === 'admin'
                                ? 'Manage all notes'
                                : 'Manage your personal notes'}
                        </p>
                    </div>
                    <Button asChild>
                        <Link href={create().url}>Create Note</Link>
                    </Button>
                </div>

                {notes.data.length === 0 ? (
                    <Card>
                        <CardContent className="py-8 text-center">
                            <p className="text-muted-foreground">
                                No notes found. Create your first note!
                            </p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {notes.data.map((note) => (
                            <Card key={note.id} className="flex flex-col">
                                <CardHeader>
                                    <CardTitle className="line-clamp-1">
                                        {note.title}
                                    </CardTitle>
                                    <CardDescription>
                                        {auth.user.role === 'admin' &&
                                            note.user && (
                                                <span>
                                                    By {note.user.name} •{' '}
                                                </span>
                                            )}
                                        {new Date(
                                            note.created_at,
                                        ).toLocaleDateString()}
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="flex-1">
                                    <p className="line-clamp-3 text-sm text-muted-foreground">
                                        {note.content}
                                    </p>
                                </CardContent>
                                <CardContent className="flex gap-2 pt-0">
                                    <Button variant="outline" size="sm" asChild>
                                        <Link
                                            href={show({ note: note.id }).url}
                                        >
                                            View
                                        </Link>
                                    </Button>
                                    <Button variant="outline" size="sm" asChild>
                                        <Link
                                            href={edit({ note: note.id }).url}
                                        >
                                            Edit
                                        </Link>
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        onClick={() => handleDelete(note.id)}
                                    >
                                        Delete
                                    </Button>
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
