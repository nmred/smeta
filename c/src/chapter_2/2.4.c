#include <stdio.h>

int main(void)
{
	int Total_Pets;
	int Cats;
	int Dogs;
	int Ponies;
	int Others;
	
	Cats = 2;
	Dogs = 1;
	Ponies = 1;
	Others = 46;
	
	Total_Pets = Cats + Dogs + Ponies + Others;
	
	printf("We have %d pets in total\n", Total_Pets);
	return 0;	
}
