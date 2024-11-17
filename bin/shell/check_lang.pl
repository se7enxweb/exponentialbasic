#!/usr/bin/perl -w
#
# $Id: check_lang.pl 9136 2002-02-04 11:31:00Z bf $
#
# This script checks the eZ publish distribution for missing
# language files. en_GB is used as reference language.
#
# Copyright (c) 2001 - eZ systems as
#
# Created on: Created on: <15-May-2001 17:09:11 bf>
# Author: Bård Farstad - <bf@ez.no>
#
# Updated on: <02-Feb-2002 17:09:11 waba>
# Author: Waba - <wabasoft@yahoo.fr>
# Added: -f, -ff, -i, stats, colors, unique mode, ...
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License as
# published by the Free Software Foundation; either version 2 of
# the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
#


use Fcntl;
use File::Find;
use File::Path;
use File::Basename;
use File::Copy;
use Getopt::Std;
use Cwd; 

use strict;

####### Main #########

my $VERSION = "0.6";
my ($Fix, $FixFiles, $Int, $Uni) = (0, 0, 0, 0);
my $Lang = "";
# See http://web.mit.edu/ueab/www/ansicodes.html
my %ANSI = (
	bold 	=> "\033[1m",
	normal	=> "\033[0m",
	bred	=> "\033[1;31m",
	byellow	=> "\033[1;33m",
	bgreen	=> "\033[1;32m"
);
my %Enk; # English keys: ($key => ($val1, $val2, ...), ...)

# as in $key=$FS1$value$FS2
my $FS1 = '@@';
my $FS2 = '@@';

my %Stats = (
	cr_files	=> 0,	# Created files
	cr_keys		=> 0,	# Created keys
	mi_files	=> 0,	# Missing files
	mi_keys		=> 0,	# Missing keys
	tot_files	=> 0,	# Total files
	tot_keys	=> 0);	# Total keys
	
# Handle ^C
$SIG{'INT'} = \&sigint;

&checkOpts;

if( my $need_rerun = go ($Uni) )
{
	print( "You should rerun this script to be sure that everything is ok. ($need_rerun file(s) concerned)\n");
}

&print_stats;

####### Functions ####

sub sigint
{
	print( "$ANSI{normal}Caught SIGINT, cleaning up and saving files...\n");
	close Reference;
	close Check;
	&print_stats;
	exit 0;
}

sub usage
{
	print(
		"Usage: \n   check_lang.pl [-f | -ff | -i] [-p] [-u] iso_code\n" .
		"\t-f (fix) completes the files with en_GB version surrounded by @@\n".
		"\t-ff (fix files) Create the missing files, don't complete them\n".
		"\t-i (interactive) ask you for a translation instead\n".
		"\t-p (plain) Don't use ANSI colors\n".
		"\t-u (unique) Preprocess files to avoid translating the same thing twice. ".
		"Usefull only with -i or to get complete statistics when interrupted.\n" .
		"E.g.\t   check_lang.pl -f no_NO\n\n".
		"There is some special translations in -i mode :\n" .
		"\t- <cr> : hit enter to skip this translation.\n".
		"\t- =    : For use with words like 'e-mail', which usually remains the same.\n".
		"\t- .x   : When using -u, selects the proposition <x>.\n" );
	exit();
}

sub print_stats
{
	my $cf = $Stats{cr_files};
	my $ck = $Stats{cr_keys};
	my $mf = $Stats{mi_files};
	my $mk = $Stats{mi_keys};
	my $tf = $Stats{tot_files};
	my $tk = $Stats{tot_keys};

	print ( "\n$ANSI{bold}Statistics :$ANSI{normal}\n");

	unless( $tk )
	{
		print( "No files processed, so no statistics available.\n" );
		return;
	}

	printf( "\tMissing files encountered : %5d (%3d %%)\n", $mf, $mf * 100 / $tf );
	printf( "\tMissing keys encountered  : %5d (%3d %%)\n", $mk, $mk * 100 / $tk );
	printf( "\tCreated files             : %5d (%3d %%)\n", $cf, $cf * 100 / $tf );
	printf( "\tCreated keys              : %5d (%3d %%)\n", $ck, $ck * 100 / $tk );
	printf( "\tTotal files encountered   : %5d\n", $tf);
	printf( "\tTotal keys encountered    : %5d\n", $tk);
	print ( "Hint: use -ff to get the missing files converted to missing keys.\n") if( $mf );
}

sub checkOpts
{
	my $count = @ARGV;

	if ( $count == 0 )
	{
		&usage;
	}

	foreach $_ (@ARGV)
	{
		if ( /^-(.+)$/ )
		{
			my $opt = $1;
			
			if( lc( $opt ) eq "i" )
			{
				$Int = 1;
			} 
			elsif( lc( $opt ) eq "u" )
			{
				$Uni = 1;
			}
			elsif( lc( $opt ) eq "f" )
			{
				$Fix = 1;
			}
			elsif( lc( $opt ) eq "ff")
			{
				$FixFiles=1;
			}
			elsif( lc( $opt ) eq 'p' )
			{
				foreach my $key (keys %ANSI)
				{
					$ANSI{$key} = '';
				}
			}
			else
			{
				&usage;
				exit 1;
			}
		}
		elsif ( /^([a-z]+_[A-Z]+)/ )
		{
			$Lang = $1;
		}
		else
		{
			&usage;
		}
	}
}

sub go
{
	# Are we grabbing translations (-> 1) or normal (-> 0) ?
	my $uni_mode = shift;
	my $need_rerun = 0;
	
	print( "Reading files a first time...\n") if( $uni_mode );
	opendir( D, ".");
	foreach my $module ( readdir(D) )
	{
		if ( $module =~ /^ez[^\.]+$/ )
		{
			print( "$ANSI{bgreen}Checking module$ANSI{normal}: $module\n" ) unless( $uni_mode );
			$need_rerun += checkModule( $module, "user", $uni_mode );
			$need_rerun += checkModule( $module, "admin", $uni_mode );

		}
	}
	close(D);

	# If in uni mode, restart in normal mode, as we have collected our datas
	if( $uni_mode )
	{
		printf( "Done (%d keys), continuing in normal mode.\n\n", int (keys %Enk));
		$need_rerun = 0;
		go( 0 );
	}
	
	return $need_rerun;
}

sub checkModule	# module, part, uni_mode (see go())
{
	my ($module, $part, $uni_mode) = @_;
	my $need_rerun = 0;
	
    opendir( Intl, "$module/$part/intl/en_GB/" );

	if( ($Int or $Fix or $FixFiles) and not $uni_mode )
	{
		mkdir( "$module/$part/intl/$Lang", 0750 );
	}

    # check language files
    foreach my $intl_file ( readdir( Intl ) )
    {
		if ( $intl_file =~ /^[^\.].*\.php\.ini$/ )
		{
			$need_rerun += processFile( $module, $part, $intl_file, $uni_mode );
	    }
	}

	closedir( Intl );

	return $need_rerun;
}

sub processFile # module, part, intl_file, uni_mode - return 1 if needs rerun
{
	my ($module, $part, $intl_file, $uni_mode) = @_;
	my $check_file = "$module/$part/intl/$Lang/$intl_file";
	my $need_rerun = 0;
	my $last_char_is_nl = 0; # Speaking about last Check line, and yes, it is ugly.
	
	# check if the language file exists
	open ( Reference, "$module/$part/intl/en_GB/$intl_file" ) or die "Can't open reference language file";
	
	$Stats{tot_files}++ if( $uni_mode or not $Uni );

	if ( open ( Check, $check_file ) )
	{
		my @ref_lines = <Reference>; 
		my @check_lines = <Check>;

		close( Reference );
		close( Check );
		
		if ( not $uni_mode and ($Fix || $Int) )
		{
			my $file_exists = 0;
			$file_exists = 1 if( -e $check_file );
			
			return unless( !$file_exists or -f $check_file ); # Writing on a non-plain file isn't a good idea
			
			umask( 026 ) unless $file_exists;
			
			open ( Check, ">>$check_file" ) or die "Can't open target file for writing";

			print Check "[strings]\n" unless $file_exists;

			$Stats{cr_files}++ unless $file_exists;
		}
	
		my @ref_array = ();
		my @check_array = ();

		my %ref_hash;	# @ref_array will act as a sorted index for %ref_hash

		# copy @ref_lines identifiers to @ref_array
		foreach $_ ( @ref_lines )
		{
			if ( /^\s*([^#[]+?)=(.*)/ )	# [spaces](chars != #,[)=(<rest-of-line>)
			{
				my( $key, $value ) = ($1, $2);

				if( not $uni_mode )
				{
					push( @ref_array, $key );
					if ( $Fix || $Int )
					{
						$ref_hash {$key} = $value;
					}
				}
				else
				{
					$Enk{$key} = [] if( not exists $Enk{$key} );
				}
				$Stats{tot_keys}++ if( $uni_mode or not $Uni );
			}
		}
		
		# check for duplicate keys in the target file
		foreach $_ ( @check_lines )
		{
			if ( /^\s*([^#[]+?)=(.*)/ )	# [spaces](chars != #,[)=(<rest-of-line>)
			{
				my $checkItem = $1;
				my $checkValue = $2;

				if( not $uni_mode )
				{
					if ( $checkValue =~ /=/ )
					{
						print( "\t$ANSI{byellow}Possible missing carriage return".
							"$ANSI{normal} in $check_file :: $checkItem\n" );
					}

					if ( $checkValue =~ /$FS1.*$FS2/ )
					{
						print( "\t$ANSI{byellow}Possible untranslated string $ANSI{normal}: $check_file :: $checkItem\n" );
					}
					
					if ( grep( $_ eq $checkItem, @check_array ) )
					{
						chomp $checkItem;
						print( "\t$ANSI{bred}Duplicate key$ANSI{normal} : $check_file :: $checkItem\n" );
					}
					else
					{
						push( @check_array, $checkItem );
					}
				}
				else
				{
					Enk_add( $checkItem, $checkValue );
				}
			}
		}
		
		$last_char_is_nl = 1 if( $#check_lines > 0 and chop( $check_lines[$#check_lines] ) eq "\n" );
		return( 0 ) if( $uni_mode );
		
		# check that all the keys in the ref file are present in the target one
		foreach my $refItem ( @ref_array ) 
		{
			my $found = 0;
			
			if ( ! grep( $_ eq $refItem, @check_array ) )
			{
				print( "\t$ANSI{bred}Missing key$ANSI{normal} : $check_file :: $refItem\n" );
				$Stats{mi_keys}++;
				if( $Fix )
				{
					fixAddKey ($check_file, $refItem, $ref_hash{$refItem}, \$last_char_is_nl );
					$need_rerun = 1;
				}
				elsif( $Int )
				{
					intAddKey ($check_file, $refItem, $ref_hash{$refItem}, \$last_char_is_nl);
					$need_rerun = 1;
				}
			}
		}
		
		if ( $Fix || $Int )
		{
			close ( Check );
		}
	}
	elsif( not $uni_mode )
	{
		print( "\t$ANSI{bred}Missing file$ANSI{normal} : $intl_file\n" );
		$Stats{mi_files}++;
		if( $Fix || $Int || $FixFiles )
		{
			umask( 026 );
			if( !open ( Check, ">$check_file" ) )
			{
				print "Can't touch target file $check_file ";
			}
			else
			{
				print Check "[strings]\n";
				close( Check );
				$Stats{mi_files}++;
			}
			$need_rerun = 1;
		}
	}

	return $need_rerun;
}

sub fixAddKey
{
	my ( $check_file, $key, $value, $last_char_is_nl ) = @_;

	print Check "\n" unless( $$last_char_is_nl );
	print Check "$key=\@\@$value\@\@\n";
	
	$$last_char_is_nl = 1;
	
	$Stats{cr_keys}++;
}

sub intAddKey
{
	my ( $check_file, $key, $value, $last_char_is_nl ) = @_;
	my $ps2;
	my $trans_id = 0;

	if( $Uni and exists $Enk{$key} and @{$Enk{$key}} )
	{
		print( "This key has aldready been translated as :\n" );
		
		foreach my $translation ( @{$Enk{$key}} )
		{
			print( "\t$trans_id. \"". $ANSI{bold} . $translation . $ANSI{normal} . "\"\n" );
			$trans_id++;
		}
	}
	
	if( exists $ENV{'PS2'} )
	{
		$ps2 = $ENV{'PS2'}
	}
	else
	{
		$ps2 = '>';
	}

	print( "Trans. for (".
		$ANSI{bold} ."$key" .$ANSI{normal}.
		") \"". 
		$ANSI{bold} .$value. $ANSI{normal}.
		"\" :\n$ps2 " );
	
	my $translation = <STDIN>;
	chomp $translation;

	$translation = $value if( $translation eq "=" );
	return if( ! $translation );

	if( $trans_id and $translation =~ /^\.(\d+)$/ and $1 <= $trans_id )
	{
		$translation = ${$Enk{$key}}[$1];
	}

	Enk_add( $key, $translation ) if( $Uni );

	print Check "\n" unless( $$last_char_is_nl );
	print Check "$key=$translation\n";

	$$last_char_is_nl = 1;
	
	$Stats{cr_keys}++;
}

# Adds $value to an existing $key and only if not creating a duplicate
sub Enk_add
{
	my ($key, $value) = @_;

	return if( not defined $key or not defined $value );

	return unless( exists $Enk{$key} );

	push( @{$Enk{$key}}, $value) unless ( grep( $_ eq $value, @{$Enk{$key}} ) );
	
}
	
